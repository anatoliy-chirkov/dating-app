<?php

namespace Client\Controllers;

use Admin\Repositories\CounterRepository;
use Client\Controllers\Shared\SiteController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\Http\Request;
use Shared\Core\Validation\Validator;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;
use Client\Services\AuthService;
use Client\Services\GoogleGeoService\GoogleGeoService;
use Client\Services\NotificationService\NotificationService;
use Client\Services\UserService\UserService;
use Shared\Core\App;

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
            $validator = App::get('validator');

            $isValid = $validator->isValid($request->post(), [
                'email' => 'required,email',
                'password' => 'required',
            ]);

            if (!$isValid) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $validator->getFirstError());
                return $this->render();
            }

            /** @var AuthService $authService */
            $authService = App::get('authService');

            try {
                $isValidPassword = $authService->checkEmailAndPassword(
                    $request->post('email'),
                    $request->post('password')
                );
            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $e->getMessage());
                return $this->render();
            }

            if (!$isValidPassword) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', 'Неверный пароль');
                return $this->render();
            }

            /** @var UserRepository $userRepository */
            $userRepository = App::get('user');
            $user = $userRepository->getByEmail($request->post('email'));

            $authService->setUpToken($user['id']);

            $request->redirect('/');
        }

        return $this->render();
    }

    public function register(Request $request)
    {
        $viewPayload = [
            'googleApiKey' => App::get('env')->get('GOOGLE_API_KEY'),
            'goals' => App::get('goal')->getAll(),
        ];

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator');
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');

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
            $googleGeoService = App::get('google_geo_service');

            if (!$googleGeoService->isValidCityString($request->post('city'))) {
                $notificationService->set('error', 'Попробуйте выбрать город из списка еще раз');
                return $this->render($viewPayload);
            }

            /** @var UserService $userService */
            $userService = App::get('userService');

            try {
                $user = $userService->createUser($request->post(), $mainPhoto);

                /** @var CounterRepository $counterRepository */
                $counterRepository = App::get('counter');
                $counters = $counterRepository->getActiveCounters();

                foreach ($counters as $counter) {
                    $counterRepository->addUserCounter($user['id'], $counter['id']);
                }

                Action::run(IAction::REGISTRATION, $user['id']);

            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $e->getMessage());
                return $this->render($viewPayload);
            }

            /** @var AuthService $authService */
            $authService = App::get('authService');
            $authService->setUpToken($user['id']);

            $request->redirect('/');
        }

        return $this->render($viewPayload);
    }

    public function logout(Request $request)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $authService->removeToken();

        $request->redirect('/');
    }
}
