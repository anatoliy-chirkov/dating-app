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
    /** @var NotificationService $notificationService */
    private $notificationService;
    /** @var AuthService $authService */
    private $authService;
    /** @var UserRepository $userRepository */
    private $userRepository;

    public function __construct()
    {
        $this->notificationService = App::get('notificationService');
        $this->authService = App::get('authService');
        $this->userRepository = App::get('user');

        parent::__construct();
    }

    public function getProtectedMethods()
    {
        return ['logout'];
    }

    public function login(Request $request)
    {
        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->post(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if (!$validator->isValid()) {
                $this->notificationService->set('error', $validator->getErrorsAsString());
                return $this->render();
            }

            try {
                if (!$this->authService->checkEmailAndPassword(
                    $request->post('email'),
                    $request->post('password')
                )) {
                    throw new \Exception('Неверный пароль');
                }
            } catch (\Exception $e) {
                $this->notificationService->set('error', $e->getMessage());
                return $this->render();
            }

            $user = $this->userRepository->getByEmail($request->post('email'));
            $this->authService->setUpToken($user['id']);
            $request->redirect('/');
        }

        return $this->render();
    }

    public function register(Request $request)
    {
        /** @var GoogleGeoService $googleGeoService */
        $googleGeoService = App::get('googleGeoService');
        /** @var UserService $userService */
        $userService = App::get('userService');
        /** @var CounterRepository $counterRepository */
        $counterRepository = App::get('counter');

        $viewPayload = [
            'googleApiKey' => App::get('env')->get('GOOGLE_API_KEY'),
            'goals' => App::get('goal')->getAll(),
        ];

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator', $request->all(), [
                'sex' => 'required|in:man,woman',
                'age' => 'required|integer',
                'name' => 'required|string|max:100',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'repeatPassword' => 'required|same:password',
                'city' => 'required',
                'goalId' => 'required|array',
                'goalId.*' => 'integer',
                'mainPhoto' => 'nullable|image|max:5000',
            ]);

            if (!$validator->isValid()) {
                $this->notificationService->set('error', $validator->getErrorsAsString());
                return $this->render($viewPayload);
            } else if (!$googleGeoService->isValidCityString($request->post('city'))) {
                $this->notificationService->set('error', 'Попробуйте выбрать город из списка еще раз');
                return $this->render($viewPayload);
            }

            try {
                $user = $userService->createUser($request->post(), $request->file('mainPhoto'));
                $counters = $counterRepository->getActiveCounters();

                foreach ($counters as $counter) {
                    $counterRepository->addUserCounter($user['id'], $counter['id']);
                }

                Action::run(IAction::REGISTRATION, $user['id']);

            } catch (\Exception $e) {
                $this->notificationService->set('error', $e->getMessage());
                return $this->render($viewPayload);
            }

            $this->authService->setUpToken($user['id']);
            $request->redirect('/');
        }

        return $this->render($viewPayload);
    }

    public function logout(Request $request)
    {
        $this->authService->removeToken();
        $request->redirect('/');
    }
}
