<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\CounterRepository;
use Shared\Core\Controllers\IProtected;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Shared\Core\Validation\Validator;
use Client\Services\NotificationService\NotificationService;

class CounterController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all', 'create', 'edit'];
    }

    public function all()
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = App::get('counter');

        return $this->render([
            'counters' => $counterRepository->counters(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->post(), [
                'name' => 'required'
            ]);

            if (!$validator->isValid()) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $validator->getErrorsAsString());
                return $this->render();
            }

            /** @var CounterRepository $counterRepository */
            $counterRepository = App::get('counter');
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
        $counterRepository = App::get('counter');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->post(), [
                'id' => 'required',
                'name' => 'required'
            ]);

            if (!$validator->isValid()) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $validator->getErrorsAsString());
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
        $counterRepository = App::get('counter');

        return $this->render([
            'counterActions' => $counterRepository->counterActions($counterId),
            'counterId' => $counterId,
            'counterName' => $counterRepository->counter($counterId)['name'],
        ]);
    }

    public function createCounterAction(Request $request, int $counterId)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = App::get('counter');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->post(), [
                'type' => 'required',
                'actionId' => 'required',
                'multiplier' => 'required',
            ]);

            if (!$validator->isValid()) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $validator->getErrorsAsString());
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
        $counterRepository = App::get('counter');

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->post(), [
                'type' => 'required',
                'actionId' => 'required',
                'multiplier' => 'required',
            ]);

            if (!$validator->isValid()) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $validator->getErrorsAsString());
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
