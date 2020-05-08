<?php

namespace Client\Controllers;

use Admin\Repositories\CounterRepository;
use Carbon\Carbon;
use Client\Repositories\ProductRepository;
use Client\Controllers\Shared\SiteController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\DotEnv;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\BillRepository;
use Client\Repositories\PurchaseRepository;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;
use Client\Services\AuthService;
use Client\Services\CommandService\Command;
use Client\Services\NotificationService\NotificationService;

class ShopController extends SiteController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['main', 'buy', 'putMoney'];
    }

    public function main()
    {
        /** @var ProductRepository $productRepository */
        $productRepository = App::get('product');
        $boughtProducts = $productRepository->getUserProducts($this->user['id']);

        foreach ($boughtProducts as &$boughtProduct) {
            $boughtProduct['createdAt'] = Carbon::parse($boughtProduct['createdAt'])
                ->locale('ru')->isoFormat('D MMMM YYYY, HH:mm');
            $boughtProduct['expiredAt'] = Carbon::parse($boughtProduct['expiredAt'])
                ->locale('ru')->isoFormat('D MMMM YYYY, HH:mm');
        }

        $groups = $productRepository->getProductGroups();

        foreach ($groups as &$group) {
            $group['products'] = $productRepository->getProductsByGroup($group['id']);
        }

        /** @var CounterRepository $counterRepository */
        $counterRepository = App::get('counter');

        return $this->render([
            'groupsWithProducts' => $groups,
            'boughtProducts' => $boughtProducts,
            'counters' => $counterRepository->getUserCounters($this->user['id']),
        ]);
    }

    public function buy(Request $request, int $productId)
    {
        /** @var ProductRepository $productRepository */
        $productRepository = App::get('product');
        $product = $productRepository->product($productId);

        if ($product === null) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', 'Пожалусйта попробуйте позже.');
            $request->redirect('/shop');
        }

        if ($this->user['money'] < $product['price']) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', 'Недостаточно денег на счете. Пожалусйта пополните счет и повторите.');
            $request->redirect('/shop');
        }

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $userRepository->reduceMoney($this->user['id'], $product['price']);

        $activeProduct = $productRepository->getActiveUserProductByGroup($this->user['id'], $product['groupId']);
        $carbonStartExpiredAt = $activeProduct === null
            ? Carbon::now()
            : Carbon::parse($activeProduct['expiredAt'])
        ;

        $createdAt = Carbon::now()->toDateTimeString();
        $expiredAt = $carbonStartExpiredAt->addHours($product['duration'])->toDateTimeString();

        $productRepository->addProductToUser($productId, $this->user['id'], $createdAt, $expiredAt);

        /** @var PurchaseRepository $purchaseRepository */
        $purchaseRepository = App::get('purchase');
        $purchaseRepository->create($this->user['id'], $productId, $product['price']);

        foreach ($productRepository->getProductCommands($productId) as $command) {
            /** @var Command $commandObject */
            $commandObject = App::get('command');
            $commandName = $command['name'];

            if (method_exists($commandObject, $commandName)) {
                $commandObject->$commandName($this->user['id']);
            }
        }

        $request->redirect('/shop');
    }

    public function putMoney(Request $request)
    {
        $amount = $request->post('amount');

        if (empty($amount) || $amount < 1) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', 'Сумма должна быть больше 1 руб');
            $request->redirect('/shop');
        }

        /** @var BillRepository $billRepository */
        $billRepository = App::get('bill');
        $billId = $billRepository->create($amount, $this->user['id']);

        /** @var DotEnv $dotEnv */
        $dotEnv = App::get('env');
        $billPayments = new Qiwi\Api\BillPayments($dotEnv->get('QIWI_SECRET_KEY'));

        $params = [
            'publicKey' => $dotEnv->get('QIWI_PUBLIC_KEY'),
            'amount' => $amount,
            'billId' => $billId,
            'customFields' => [
                'themeCode' => 'Anatolyi-Ch1mqBoXCPB'
            ],
            'successUrl' => 'http://ankira.local/shop',
        ];

        /** @var \Qiwi\Api\BillPayments $billPayments */
        $request->redirect($billPayments->createPaymentForm($params), 'external');
    }

    public function successPutMoneyCallback(Request $request)
    {
        $data = $request->decodedJson('bill');

        $headers = getallheaders();
        $hmacHash = $headers['X-Api-Signature-SHA256'];

        if (!is_object($data) || empty($hmacHash)) {
            $this->renderJson([
                'status' => 'FAIL',
                'error' => true,
                'errorText' => 'Forbidden',
            ]);
        }

        $invoiceParameters = "{$data->amount->currency}|{$data->amount->value}|{$data->billId}|{$data->siteId}|{$data->status->value}";

        /** @var DotEnv $dotEnv */
        $dotEnv = App::get('env');
        $hash = hash_hmac('sha256', $invoiceParameters, $dotEnv->get('QIWI_SECRET_KEY'));

        if ($hash !== $hmacHash) {
            $this->renderJson([
                'status' => 'FAIL',
                'error' => true,
                'errorText' => 'Forbidden',
            ]);
        }

        /** @var BillRepository $billRepository */
        $billRepository = App::get('bill');

        if ($billRepository->getOne((int) $data->billId)['paidAt'] !== null) {
            $this->renderJson([
                'status' => 'FAIL',
                'error' => true,
                'errorText' => 'Bill already processed',
            ]);
        }

        $billRepository->setPaid((int) $data->billId, str_replace('T', ' ', $data->status->datetime));
        $userId = $billRepository->getUserId((int) $data->billId);

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $userRepository->increaseMoney($userId, (float) $data->amount->value);
        Action::run(IAction::PUT_MONEY, $userId);

        $this->renderJson([
            'status' => 'OK',
            'error' => false,
            'errorText' => '',
        ]);
    }
}
