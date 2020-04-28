<?php

namespace Client\Controllers;

use Client\Controllers\Shared\SiteController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Shared\Core\Validation\Validator;
use Client\Repositories\GoalRepository;
use Client\Repositories\ImageRepository;
use Client\Repositories\TokenRepository;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\AuthService;
use Client\Services\GoogleGeoService\GoogleGeoService;
use Client\Services\ImageService;
use Client\Services\NotificationService\NotificationService;

class ProfileController extends SiteController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['settings', 'edit', 'addPhoto', 'chooseMainPhoto', 'deletePhoto', 'changePassword'];
    }

    public function settings()
    {
        $me = $this->getMe();
        $images = $this->getImages($me['id']);
        return $this->render(['images' => $images, 'me' => $me, 'LAYOUT_NOTIFICATION_OFF' => true]);
    }

    public function edit(Request $request)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $me = $authService->getUser();

        $GOOGLE_API_KEY = App::get('env')->get('GOOGLE_API_KEY');
        $NOT_CHANGED_CITY_STRING = 'notChanged';

        /** @var GoalRepository $goalRepository */
        $goalRepository = App::get('goal');
        $userGoalsId = [];

        foreach ($goalRepository->getUserGoals($me['id']) as $userGoal) {
            $userGoalsId[] = $userGoal['id'];
        }

        $viewPayload = [
            'googleApiKey' => $GOOGLE_API_KEY,
            'cityString' => $NOT_CHANGED_CITY_STRING,
            'LAYOUT_NOTIFICATION_OFF' => true,
            'goals' => $goalRepository->getAll(),
            'userGoalsId' => $userGoalsId,
        ];

        if ($request->isPost()) {
            /** @var Validator $validator */
            $validator = App::get('validator');
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');

            $CITY_HAS_BEEN_CHANGED = $request->post('city') !== $NOT_CHANGED_CITY_STRING;

            if (
                !$validator->isValid($request->post(), ['name' => 'required', 'age' => 'required', 'city' => 'required'])
                || !$validator->validateXss($request->post('weight'))
                || !$validator->validateXss($request->post('height'))
                || !$validator->validateXss($request->post('about'))
            ) {
                $notificationService->set('error', 'Обязательные поля не заполнены или данные содержат недопустимые символы');
                return $this->render($viewPayload);
            }

            /** @var GoogleGeoService $googleGeoService */
            $googleGeoService = App::get('google_geo_service');

            if (
                $CITY_HAS_BEEN_CHANGED
                && !$googleGeoService->isValidCityString($request->post('city'))
            ) {
                $notificationService->set('error', 'Попробуйте выбрать город из списка еще раз');
                return $this->render($viewPayload);
            }

            if ($CITY_HAS_BEEN_CHANGED) {
                /** @var GoogleGeoService $googleGeoService */
                $googleGeoService = App::get('google_geo_service');
                $googleGeoId = $googleGeoService->saveIfNotExistAndGetId($request->post('city'));
            } else {
                $googleGeoId = $me['googleGeoId'];
            }

            /** @var UserRepository $userRepository */
            $userRepository = App::get('user');
            $userRepository->update(
                $me['id'],
                $request->post('name'),
                $request->post('age'),
                $googleGeoId,
                $request->post('height', null),
                $request->post('weight', null),
                $request->post('about', null)
            );

            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('success', 'Данные обновлены');
        }

        return $this->render($viewPayload);
    }

    public function addPhoto(Request $request)
    {
        try {
            $file = $request->file('photo');

            // VALIDATION
            if ($file === null) {
                throw new \Exception('Фото - обязательное поле');
            }

            if (!in_array($file->getExtension(), ['jpg', 'jpeg'])) {
                throw new \Exception('Файл должен иметь расширение jpg / jpeg');
            }

            if ($file->getSizeInKb() > 5000) {
                throw new \Exception('Максимальный размер файла 5 мегабайт, пожалуйста сожмите фото или загрузите другое');
            }

            // SAVING
            /** @var AuthService $authService */
            $authService = App::get('authService');
            $me = $authService->getUser();

            /** @var ImageService $imageService */
            $imageService = App::get('image_service');
            $imageService->save($file, $me['id']);

            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('success', 'Фото добавлено');
        } catch (\Exception $e) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', $e->getMessage());
        }

        $request->redirect('/profile#content');
    }

    public function chooseMainPhoto(Request $request)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $me = $authService->getUser();

        /** @var Validator $validator */
        $validator = App::get('validator');

        if (!$validator->isValid($request->post(), ['mainPhoto' => 'required'])) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', $validator->getFirstError());
            $request->redirect('/profile#content');
        }

        /** @var ImageRepository $imageRepository */
        $imageRepository = App::get('image');
        $imageRepository->markImageAsMain((int) $request->post('mainPhoto'), $me['id']);

        /** @var NotificationService $notificationService */
        $notificationService = App::get('notificationService');
        $notificationService->set('success', 'Главное фото профиля обновлено');

        $request->redirect('/profile#content');
    }

    public function deletePhoto(Request $request)
    {
        $photoIds = $request->post('photo');

        /** @var AuthService $authService */
        $authService = App::get('authService');
        $me = $authService->getUser();

        /** @var ImageService $imageService */
        $imageService = App::get('image_service');

        try {
            foreach ($photoIds as $photoId) {
                $imageService->deleteOne($photoId, $me['id']);
            }

            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('success', 'Фото удалены');
        } catch (\Exception $e) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', $e->getMessage());
        }

        $request->redirect('/profile#content');
    }

    public function changePassword(Request $request)
    {
        if ($request->isPost()) {
            /** @var AuthService $authService */
            $authService = App::get('authService');
            $me = $authService->getUser();

            /** @var Validator $validator */
            $validator = App::get('validator');

            $isValidInGeneral = $validator->isValid($request->post(), [
                'oldPassword' => 'required',
                'newPassword' => 'required',
                'newPasswordRepeat' => 'required',
            ]);

            try {
                // VALIDATION
                if (!$isValidInGeneral) {
                    throw new \Exception($validator->getFirstError());
                }

                $isValidPassword = $authService->checkPasswordsByUserId($me['id'], $request->post('oldPassword'));

                if (!$isValidPassword) {
                    throw new \Exception('Старый пароль введен неверно');
                }

                if ($request->post('newPassword') !== $request->post('newPasswordRepeat')) {
                    throw new \Exception('Новые пароли не совпадают');
                }

                // LOGIC
                /** @var UserRepository $userRepository */
                $userRepository = App::get('user');
                $userRepository->setNewPasswordHash(
                    $me['id'], $authService->hashPassword($request->post('newPassword'))
                );

                if ($request->post('logoutEverywhere') === 'on') {
                    /** @var TokenRepository $tokenRepository */
                    $tokenRepository = App::get('token');
                    $tokenRepository->removeAllUserTokens($me['id']);

                    $authService->setUpToken($me['id']);
                }

                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('success', 'Пароль изменен');
            } catch (\Exception $e) {
                /** @var NotificationService $notificationService */
                $notificationService = App::get('notificationService');
                $notificationService->set('error', $e->getMessage());
            }
        }

        return $this->render(['LAYOUT_NOTIFICATION_OFF' => true]);
    }

    private function getImages(int $userId)
    {
        /** @var ImageRepository $imageRepository */
        $imageRepository = App::get('image');
        return $imageRepository->getUserImages($userId);
    }

    private function getMe()
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        return $authService->getUser();
    }
}
