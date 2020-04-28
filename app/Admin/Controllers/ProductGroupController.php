<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\ProductRepository;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Shared\Core\Validation\Validator;
use Client\Services\NotificationService\NotificationService;

class ProductGroupController extends AdminController
{
    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = App::get('product');
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
            $validator = App::get('validator');

            if (!$validator->isValid($request->post(), [
                'name' => 'required',
                'about' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
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
            $validator = App::get('validator');

            if (!$validator->isValid($request->post(), [
                'id' => 'required',
                'name' => 'required',
                'about' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
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
