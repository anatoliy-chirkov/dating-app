<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\ProductRepository;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Services\NotificationService\NotificationService;

class ProductGroupController extends AdminController
{
    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = ServiceContainer::getInstance()->get('product_repository');
    }

    public function all()
    {
        return $this->render([
            'groups' => $this->productRepository->productGroups(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), [
                'name' => 'required',
                'about' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
            } else {
                $this->productRepository->addProductGroup(
                    $request->post('name'),
                    $request->post('about'),
                    !empty($request->post('isActive'))
                );

                $request->redirect('/product-groups');
            }
        }

        return $this->render();
    }

    public function edit(Request $request, $id)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), [
                'id' => 'required',
                'name' => 'required',
                'about' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
            } else {
                $this->productRepository->editProductGroup(
                    $request->post('id'),
                    $request->post('name'),
                    $request->post('about'),
                    !empty($request->post('isActive'))
                );

                $request->redirect('/product-groups/' . $id);
            }
        }

        return $this->render([
            'group' => $this->productRepository->productGroup($id)
        ]);
    }
}
