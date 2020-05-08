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
        $images = $this->getImages($this->user['id']);
        return $this->render(['images' => $images, 'LAYOUT_NOTIFICATION_OFF' => true]);
    }

    public function edit(Request $request)
    {
        $GOOGLE_API_KEY = App::get('env')->get('GOOGLE_API_KEY');
        $NOT_CHANGED_CITY_STRING = 'notChanged';

        /** @var GoalRepository $goalRepository */
        $goalRepository = App::get('goal');
        $userGoalsId = [];

        foreach ($goalRepository->getUserGoals($this->user['id']) as $userGoal) {
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
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');

            $CITY_HAS_BEEN_CHANGED = $request->post('city') !== $NOT_CHANGED_CITY_STRING;

            /** @var Validator $validator */
            $validator = App::get('validator', $request->all(), [
                'name' => 'required',
                'age' => 'required|integer',
                'city' => 'required',
                'height' => 'nullable|integer',
                'weight' => 'nullable|integer',
                'about' => 'nullable',
            ]);

            if (!$validator->isValid()) {
                $notificationService->set('error', $validator->getErrorsAsString());
                return $this->render($viewPayload);
            }

            /** @var GoogleGeoService $googleGeoService */
            $googleGeoService = App::get('googleGeoService');

            if (
                $CITY_HAS_BEEN_CHANGED
                && !$googleGeoService->isValidCityString($request->post('city'))
            ) {
                $notificationService->set('error', 'Попробуйте выбрать город из списка еще раз');
                return $this->render($viewPayload);
            }

            if ($CITY_HAS_BEEN_CHANGED) {
                /** @var GoogleGeoService $googleGeoService */
                $googleGeoService = App::get('googleGeoService');
                $googleGeoId = $googleGeoService->saveIfNotExistAndGetId($request->post('city'));
            } else {
                $googleGeoId = $this->user['googleGeoId'];
            }

            /** @var UserRepository $userRepository */
            $userRepository = App::get('user');
            $userRepository->update(
                $this->user['id'],
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
            /** @var Validator $validator */
            $validator = App::get('validator', $request->all(), [
                'photo' => 'required|image|max:5000',
            ]);

            if (!$validator->isValid()) {
                throw new \Exception($validator->getErrorsAsString());
            }

            // SAVING
            /** @var ImageService $imageService */
            $imageService = App::get('imageService');
            $imageService->save($request->file('photo'), $this->user['id']);

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
        /** @var Validator $validator */
        $validator = App::get('validator', $request->all(), [
            'mainPhoto' => 'required'
        ]);

        if (!$validator->isValid()) {
            /** @var NotificationService $notificationService */
            $notificationService = App::get('notificationService');
            $notificationService->set('error', $validator->getErrorsAsString());
            $request->redirect('/profile#content');
        }

        /** @var ImageRepository $imageRepository */
        $imageRepository = App::get('image');
        $imageRepository->markImageAsMain((int) $request->post('mainPhoto'), $this->user['id']);

        /** @var NotificationService $notificationService */
        $notificationService = App::get('notificationService');
        $notificationService->set('success', 'Главное фото профиля обновлено');

        $request->redirect('/profile#content');
    }

    public function deletePhoto(Request $request)
    {
        $photoIds = $request->post('photo');

        /** @var ImageService $imageService */
        $imageService = App::get('imageService');

        try {
            foreach ($photoIds as $photoId) {
                $imageService->deleteOne($photoId, $this->user['id']);
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

            try {
                /** @var Validator $validator */
                $validator = App::get('validator', $request->all(), [
                    'oldPassword' => 'required',
                    'newPassword' => 'required|min:6',
                    'newPasswordRepeat' => 'required|same:newPassword',
                ]);

                if (!$validator->isValid()) {
                    throw new \Exception($validator->getErrorsAsString());
                } else if (!$authService->checkPasswordsByUserId($this->user['id'], $request->post('oldPassword'))) {
                    throw new \Exception('Старый пароль введен неверно');
                }

                /** @var UserRepository $userRepository */
                $userRepository = App::get('user');
                $userRepository->setNewPasswordHash(
                    $this->user['id'], $authService->hashPassword($request->post('newPassword'))
                );

                if ($request->post('logoutEverywhere') === 'on') {
                    /** @var TokenRepository $tokenRepository */
                    $tokenRepository = App::get('token');
                    $tokenRepository->removeAllUserTokens($this->user['id']);
                    $authService->setUpToken($this->user['id']);
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
}
