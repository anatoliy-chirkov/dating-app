<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\BillRepository;
use Admin\Repositories\PurchaseRepository;
use Carbon\Carbon;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;

class PaymentController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['bills', 'purchases'];
    }

    public function bills()
    {
        return $this->render();
    }

    public function purchases()
    {
        return $this->render();
    }

    public function searchBills(Request $request)
    {
        $offset = (int) $request->get('start');
        $length = (int) $request->get('length');

        /** @var BillRepository $billRepository */
        $billRepository = ServiceContainer::getInstance()->get('bill_repository');

        $billsCount = $billRepository->count();
        $billsHTML = [];

        foreach ($billRepository->search(null, null, null, $offset, $length) as $bill) {
            $imgLink = str_replace('admin.', '', $_SERVER['HTTP_HOST']) . $bill['userImgLink'];

            $user = <<<HTML
                <img src="http://{$imgLink}" alt="Avatar">
                <span>{$bill['userName']}, {$bill['userAge']}</span>
                <span class="cell-detail-description">{$bill['userCity']}</span>
HTML;

            $billsHTML[] = [
                Carbon::parse($bill['createdAt'])->locale('en')->isoFormat('D MMMM YYYY, HH:mm'),
                "{$bill['amount']} rub",
                $user,
                !empty($bill['paidAt'])
                    ? '<span class="badge badge-success"><span class="icon mdi mdi-check"></span> Paid</span>'
                    : '<span class="badge badge-warning"><span class="icon mdi mdi-time"></span> Waiting</span>'
            ];
        }

        $this->renderJson([
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $billsCount,
            'recordsFiltered' => $billsCount,
            'data' => $billsHTML,
        ]);
    }

    public function searchPurchases(Request $request)
    {
        $offset = (int) $request->get('start');
        $length = (int) $request->get('length');

        /** @var PurchaseRepository $purchaseRepository */
        $purchaseRepository = ServiceContainer::getInstance()->get('purchase_repository');

        $purchasesCount = $purchaseRepository->count();
        $purchasesHTML = [];

        foreach ($purchaseRepository->search(null, null, null, $offset, $length) as $purchase) {
            $imgLink = str_replace('admin.', '', $_SERVER['HTTP_HOST']) . $purchase['userImgLink'];

            $user = <<<HTML
                <img src="http://{$imgLink}" alt="Avatar">
                <span>{$purchase['userName']}, {$purchase['userAge']}</span>
                <span class="cell-detail-description">{$purchase['userCity']}</span>
HTML;
            $product = <<<HTML
                <span>{$purchase['productGroupName']}, {$purchase['productName']}</span>
                <span class="cell-detail-description">{$purchase['productPrice']} rub</span>
HTML;

            $purchasesHTML[] = [
                Carbon::parse($purchase['createdAt'])->locale('en')->isoFormat('D MMMM YYYY, HH:mm'),
                $product,
                $user
            ];
        }

        $this->renderJson([
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $purchasesCount,
            'recordsFiltered' => $purchasesCount,
            'data' => $purchasesHTML,
        ]);
    }
}
