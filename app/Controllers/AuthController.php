<?php

namespace Controllers;

use Controllers\Shared\SiteController;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Repositories\UserRepository\UserRepository;
use Services\AuthService;
use Services\GoogleGeoService\GoogleGeoService;
use Services\NotificationService\NotificationService;
use Services\UserService\UserService;

class AuthController extends SiteController implements IProtected
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
        $viewPayload = [
            'googleApiKey' => ServiceContainer::getInstance()->get('env')->get('GOOGLE_API_KEY'),
            'goals' => ServiceContainer::getInstance()->get('goal_repository')->getAll(),
        ];

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = ServiceContainer::getInstance()->get('validator');
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');

            $isValid = $validator->isValid($request->post(), [
                'sex' => 'required',
                'age' => 'required',
                'name' => 'required',
                'email' => 'required,email',
                'password' => 'required',
                'repeatPassword' => 'required',
                'city' => 'required',
                'goalId' => 'required',
            ]);

            if (!$isValid) {
                $notificationService->set('error', $validator->getFirstError());
                return $this->render($viewPayload);
            }

            $mainPhoto = $request->file('main_photo');

            // TODO: move this logic to validator
            if ($mainPhoto !== null) {
                if (!in_array($mainPhoto->getExtension(), ['jpg', 'jpeg'])) {
                    $notificationService->set('error', 'Файл должен иметь расширение jpg / jpeg');
                    return $this->render($viewPayload);
                }

                if ($mainPhoto->getSizeInKb() > 5000) {
                    $notificationService->set('error', 'Максимальный размер файла 5 мегабайт, пожалуйста сожмите фото или загрузите другое');
                    return $this->render($viewPayload);
                }
            }

            if ($request->post('password') !== $request->post('repeatPassword')) {
                $notificationService->set('error', 'Пароли не совпадают');
                return $this->render($viewPayload);
            }

            /** @var GoogleGeoService $googleGeoService */
            $googleGeoService = ServiceContainer::getInstance()->get('google_geo_service');

            if (!$googleGeoService->isValidCityString($request->post('city'))) {
                $notificationService->set('error', 'Попробуйте выбрать город из списка еще раз');
                return $this->render($viewPayload);
            }

            /** @var UserService $userService */
            $userService = ServiceContainer::getInstance()->get('user_service');

            try {
                $user = $userService->createUser($request->post(), $mainPhoto);
            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = ServiceContainer::getInstance()->get('notification_service');
                $notificationService->set('error', $e->getMessage());
                return $this->render($viewPayload);
            }

            /** @var AuthService $authService */
            $authService = ServiceContainer::getInstance()->get('auth_service');
            $authService->setUpToken($user['id']);

            $request->redirect('/');
        }



        return $this->render($viewPayload);
    }

    public function logout(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $authService->removeToken();

        $request->redirect('/');
    }
}
