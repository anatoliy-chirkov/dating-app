<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Repositories\UserRepository\UserRepository;
use Services\AuthService;
use Services\NotificationService\NotificationService;
use Services\UserService\UserService;

class AuthController extends BaseController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['logout'];
    }

    public function login(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            $isValid = $validator->isValid($request->post(), [
                'email' => 'required,email',
                'password' => 'required',
            ]);

            if (!$isValid) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render();
            }

            /** @var AuthService $authService */
            $authService = ServiceContainer::getInstance()->get('auth_service');

            try {
                $isValidPassword = $authService->checkEmailAndPassword(
                    $request->post('email'),
                    $request->post('password')
                );
            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $e->getMessage());
                return $this->render();
            }

            if (!$isValidPassword) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', 'Неверный пароль');
                return $this->render();
            }

            /** @var UserRepository $userRepository */
            $userRepository = ServiceContainer::getInstance()->get('user_repository');
            $user = $userRepository->getByEmail($request->post('email'));

            $authService->setUpToken($user['id']);

            $request->redirect('/');
        }

        return $this->render();
    }

    public function register(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');

            $isValid = $validator->isValid($request->post(), [
                'sex' => 'required',
                'age' => 'required',
                'name' => 'required',
                'email' => 'required,email',
                'password' => 'required',
                'repeatPassword' => 'required',
                'placeId' => 'required',
            ]);

            if (!$isValid) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render();
            }

            // TODO: move this logic to validator
            if ($file = $request->file('main_photo')) {
                if (!in_array($file->getExtension(), ['jpg', 'jpeg'])) {
                    /** @var NotificationService $notificationService */
                    $notificationService = ServiceContainer::getInstance()->get('notification_service');
                    $notificationService->set('error', 'Файл должен иметь расширение jpg / jpeg');
                    return $this->render();
                }

                if ($file->getSizeInKb() > 5000) {
                    /** @var NotificationService $notificationService */
                    $notificationService = ServiceContainer::getInstance()->get('notification_service');
                    $notificationService->set('error', 'Максимальный размер файла 5 мегабайт, пожалуйста сожмите фото или загрузите другое');
                    return $this->render();
                }
            }

            if ($request->post('password') !== $request->post('repeatPassword')) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', 'Пароли не совпадают');
                return $this->render();
            }

            /** @var UserService $userService */
            $userService = ServiceContainer::getInstance()->get('user_service');

            try {
                $user = $userService->createUser($request->post(), $request->file('main_photo'));

                ServiceContainer::getInstance()->get('google_geo_service')
                    ->saveByPlaceId($request->post('placeId'));
            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $e->getMessage());
                return $this->render();
            }

            /** @var AuthService $authService */
            $authService = ServiceContainer::getInstance()->get('auth_service');
            $authService->setUpToken($user['id']);

            $request->redirect('/');
        }

        return $this->render();
    }

    public function logout(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $authService->removeToken();

        $request->redirect('/');
    }
}
