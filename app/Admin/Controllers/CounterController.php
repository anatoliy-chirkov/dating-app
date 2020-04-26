<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\CounterRepository;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Services\NotificationService\NotificationService;

class CounterController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all', 'create', 'edit'];
    }

    public function all()
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        return $this->render([
            'counters' => $counterRepository->counters(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), ['name' => 'required'])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render();
            }

            /** @var CounterRepository $counterRepository */
            $counterRepository = ServiceContainer::getInstance()->get('counter_repository');
            $counterRepository->createCounter(
                $request->post('name'), !empty($request->post('isActive')), $request->post('about')
            );

            $request->redirect('/counters');
        }

        return $this->render();
    }

    public function edit(Request $request, int $id)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), ['id' => 'required', 'name' => 'required'])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render();
            }

            $counterRepository->updateCounter($request->post('id'), $request->post('name'),
                !empty($request->post('isActive')), $request->post('about')
            );

            $request->redirect('/counters/' . $id);
        }

        return $this->render([
            'counter' => $counterRepository->counter($id),
        ]);
    }

    public function counterActions(Request $request, int $counterId)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        return $this->render([
            'counterActions' => $counterRepository->counterActions($counterId),
            'counterId' => $counterId,
            'counterName' => $counterRepository->counter($counterId)['name'],
        ]);
    }

    public function createCounterAction(Request $request, int $counterId)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), [
                'type' => 'required',
                'actionId' => 'required',
                'multiplier' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render([
                    'actions' => $counterRepository->actions(),
                ]);
            }

            $counterRepository->addCounterAction(
                $counterId, $request->post('actionId'), $request->post('type'),
                $request->post('multiplier'), $request->post('counterLimit'),
                $request->post('actionLimit'), $request->post('productId')
            );

            $request->redirect('/counters/' . $counterId . '/actions');
        }

        return $this->render([
            'actions' => $counterRepository->actions(),
            'counterId' => $counterId,
            'counterName' => $counterRepository->counter($counterId)['name'],
        ]);
    }

    public function editCounterAction(Request $request, int $counterId, int $id)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            if (!$validator->isValid($request->post(), [
                'type' => 'required',
                'actionId' => 'required',
                'multiplier' => 'required',
            ])) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render([
                    'actions' => $counterRepository->actions(),
                ]);
            }

            $counterRepository->updateCounterAction(
                $id, $request->post('actionId'), $request->post('type'),
                $request->post('multiplier'), $request->post('counterLimit'),
                $request->post('actionLimit'), $request->post('productId')
            );

            $request->redirect('/counters/' . $counterId . '/actions');
        }

        return $this->render([
            'actions' => $counterRepository->actions(),
            'counterAction' => $counterRepository->counterAction($id),
            'counterId' => $counterId,
            'counterName' => $counterRepository->counter($counterId)['name'],
        ]);
    }
}
