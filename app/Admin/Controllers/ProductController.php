<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\AdvantageRepository;
use Admin\Repositories\CounterRepository;
use Admin\Repositories\PusherRepository;
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
        return ['advantages', 'pushers', 'counters', 'createAdvantage', 'createPusher',
            'createCounter', 'editAdvantage', 'editPusher'];
    }

    // SELECT

    public function advantages()
    {
        /** @var AdvantageRepository $advantageRepository */
        $advantageRepository = ServiceContainer::getInstance()->get('advantage_repository');

        return $this->render([
            'advantages' => $advantageRepository->advantages(),
        ]);
    }

    public function pushers()
    {
        /** @var PusherRepository $pusherRepository */
        $pusherRepository = ServiceContainer::getInstance()->get('pusher_repository');

        return $this->render([
            'pushers' => $pusherRepository->pushers(),
        ]);
    }

    public function counters()
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        return $this->render([
            'counters' => $counterRepository->counters(),
        ]);
    }

    // CREATE

    public function createAdvantage(Request $request)
    {
        if ($request->isPost() && $this->saveAdvantage($request, 'create')) {
            $request->redirect('/products/advantages');
        }

        /** @var AdvantageRepository $advantageRepository */
        $advantageRepository = ServiceContainer::getInstance()->get('advantage_repository');

        return $this->render([
            'permissions' => $advantageRepository->permissions(),
            'accesses' => $advantageRepository->accesses(),
            'groups' => $advantageRepository->advantageGroups(),
        ]);
    }

    public function createPusher(Request $request)
    {
        if ($request->isPost() && $this->savePusher($request, 'create')) {
            $request->redirect('/products/pushers');
        }

        /** @var PusherRepository $pusherRepository */
        $pusherRepository = ServiceContainer::getInstance()->get('pusher_repository');

        return $this->render([
            'pusherCommands' => $pusherRepository->commands()
        ]);
    }

    public function createCounter(Request $request)
    {
        if ($request->isPost()) {
            $request->redirect('/products/counters');
        }

        return $this->render();
    }

    // UPDATE

    public function editAdvantage(Request $request, int $id)
    {
        /** @var AdvantageRepository $advantageRepository */
        $advantageRepository = ServiceContainer::getInstance()->get('advantage_repository');

        if ($request->isPost() && $this->saveAdvantage($request, 'update')) {
            $request->redirect('/products/advantages');
        }

        $advantagePermissions = $advantageRepository->advantagePermissions($id);
        $advantagePermissionsId = [];
        foreach ($advantagePermissions as $advantagePermission) {
            $advantagePermissionsId[] = $advantagePermission['permissionId'];
        }

        return $this->render([
            'permissions' => $advantageRepository->permissions(),
            'accesses' => $advantageRepository->accesses(),
            'groups' => $advantageRepository->advantageGroups(),
            'advantage' => $advantageRepository->advantage($id),
            'advantagePermissionsId' => $advantagePermissionsId
        ]);
    }

    public function editPusher(Request $request, int $id)
    {
        if ($request->isPost() && $this->savePusher($request, 'update')) {
            $request->redirect('/products/pushers');
        }

        /** @var PusherRepository $pusherRepository */
        $pusherRepository = ServiceContainer::getInstance()->get('pusher_repository');

        return $this->render([
            'pusherCommands' => $pusherRepository->commands(),
            'pusher' => $pusherRepository->pusher($id),
        ]);
    }

    // PRIVATE

    private function savePusher(Request $request, string $type): bool
    {
        /** @var PusherRepository $pusherRepository */
        $pusherRepository = ServiceContainer::getInstance()->get('pusher_repository');

        // VALIDATE
        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');
        $validate = [
            'name' => 'required',
            'pusherCommandId' => 'required',
        ];
        if ($type === 'update') {
            $validate = array_merge($validate, ['id' => 'required']);
        }
        $isValid = $validator->isValid($request->post(), $validate);
        // VALIDATE END

        if (!$isValid) {
            // VALIDATION ERROR (!)
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', $validator->getFirstError());
            return false;
        } else {
            // SAVING
            $args = [
                $request->post('name'),
                $request->post('pusherCommandId'),
                $request->post('price', 0),
                !empty($request->post('isActive'))
            ];
            if ($type === 'update') {
                array_unshift($args, $request->post('id'));
                $pusherRepository->updatePusher(...$args);
            } else {
                $pusherRepository->addPusher(...$args);
            }
            // END SAVING

            return true;
        }
    }

    private function saveAdvantage(Request $request, string $type): bool
    {
        /** @var AdvantageRepository $advantageRepository */
        $advantageRepository = ServiceContainer::getInstance()->get('advantage_repository');

        // VALIDATE
        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');
        $validate = [
            'name' => 'required',
            'permissionId' => 'required',
            'accessId' => 'required',
            'duration' => 'required',
        ];
        if ($type === 'update') {
            $validate = array_merge($validate, ['id' => 'required']);
        }
        $isValidFull = $validator->isValid($request->post(), $validate);
        $isValidGroup = $request->post('groupName') || $request->post('groupId');
        // VALIDATE END

        if (!$isValidFull || !$isValidGroup) {
            // VALIDATION ERROR (!)
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', $validator->getFirstError());
            return false;
        } else {
            // SAVING
            $groupId = $request->post('groupId');
            if ($groupId === null) {
                $groupId = $advantageRepository->addAdvantageGroup($request->post('groupName'));
            }
            /** @var AdvantageService $advantageService */
            $advantageService = ServiceContainer::getInstance()->get('advantage_service');
            $args = [
                $groupId,
                $request->post('name'),
                $request->post('permissionId'),
                $request->post('accessId'),
                $request->post('price', 0),
                $request->post('duration'),
                !empty($request->post('isActive'))
            ];
            if ($type === 'update') {
                array_unshift($args, $request->post('id'));
            }
            $advantageService->$type(...$args);
            // END SAVING

            return true;
        }
    }
}
