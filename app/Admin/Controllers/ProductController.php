<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\ProductRepository;
use Admin\Services\AdvantageService;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Services\NotificationService\NotificationService;

class ProductController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all', 'create', 'edit'];
    }

    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = ServiceContainer::getInstance()->get('product_repository');
    }

    public function all()
    {
        return $this->render([
            'products' => $this->productRepository->products(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost() && $this->save($request, 'create')) {
            $request->redirect('/products');
        }

        return $this->render([
            'actions' => $this->productRepository->actions(),
            'commands' => $this->productRepository->commands(),
            'groups' => $this->productRepository->productGroups(),
        ]);
    }

    public function edit(Request $request, int $id)
    {
        if ($request->isPost() && $this->save($request, 'update')) {
            $request->redirect('/products');
        }

        $productActions = $this->productRepository->productActions($id);
        $productActionsId = [];

        foreach ($productActions as $productAction) {
            $productActionsId[] = $productAction['actionId'];
        }

        $productCommands = $this->productRepository->productCommands($id);
        $productCommandsId = [];

        foreach ($productCommands as $productCommand) {
            $productCommandsId[] = $productCommand['commandId'];
        }

        return $this->render([
            'actions' => $this->productRepository->actions(),
            'commands' => $this->productRepository->commands(),
            'groups' => $this->productRepository->productGroups(),
            'product' => $this->productRepository->product($id),
            'productActionsId' => $productActionsId,
            'productCommandsId' => $productCommandsId,
        ]);
    }

    private function save(Request $request, string $type): bool
    {
        // VALIDATE
        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');
        $validate = [
            'name' => 'required',
            'groupId' => 'required',
        ];
        if ($type === 'update') {
            $validate = array_merge($validate, ['id' => 'required']);
        }
        $isValidOther = $validator->isValid($request->post(), $validate);
        $isValidActionCommand = $request->post('actionId') || $request->post('commandId');
        // VALIDATE END

        if (!$isValidOther || !$isValidActionCommand) {
            // VALIDATION ERROR (!)
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', 'Some fields was filled not correct');
            return false;
        } else {
            // SAVING

            if ($type === 'update') {
                $this->productRepository->updateProduct(
                    $request->post('id'),
                    $request->post('name'),
                    '',
                    $request->post('groupId'),
                    $request->post('duration', 0),
                    $request->post('price', 0),
                    !empty($request->post('isFree')),
                    !empty($request->post('isActive'))
                );

                if ($request->post('actionId') !== null) {
                    $this->productRepository->removeProductActions($request->post('id'));

                    foreach ($request->post('actionId') as $actionId) {
                        $this->productRepository->addProductAction($request->post('id'), $actionId);
                    }
                }

                if ($request->post('commandId') !== null) {
                    $this->productRepository->removeProductCommands($request->post('id'));

                    foreach ($request->post('commandId') as $commandId) {
                        $this->productRepository->addProductCommand($request->post('id'), $commandId);
                    }
                }
            } else {
                $id = $this->productRepository->addProduct(
                    $request->post('name'),
                    '',
                    $request->post('groupId'),
                    $request->post('duration'),
                    $request->post('price', 0),
                    !empty($request->post('isFree')),
                    !empty($request->post('isActive'))
                );

                if ($request->post('actionId') !== null) {
                    foreach ($request->post('actionId') as $actionId) {
                        $this->productRepository->addProductAction($id, $actionId);
                    }
                }

                if ($request->post('commandId') !== null) {
                    foreach ($request->post('commandId') as $commandId) {
                        $this->productRepository->addProductCommand($id, $commandId);
                    }
                }
            }
//            /** @var AdvantageService $advantageService */
//            $advantageService = ServiceContainer::getInstance()->get('advantage_service');
//            $args = [
//                $groupId,
//                $request->post('name'),
//                $request->post('permissionId'),
//                $request->post('accessId'),
//                $request->post('price', 0),
//                $request->post('duration'),
//                !empty($request->post('isActive'))
//            ];
//            if ($type === 'update') {
//                array_unshift($args, $request->post('id'));
//            }
//            $advantageService->$type(...$args);
            // END SAVING

            return true;
        }
    }
}
