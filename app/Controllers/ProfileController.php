<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Repositories\ImageRepository;
use Repositories\TokenRepository;
use Repositories\UserRepository\UserRepository;
use Services\AuthService;
use Services\ImageService;
use Services\NotificationService\NotificationService;

class ProfileController extends BaseController
{
    public function settings()
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        /** @var ImageRepository $imageRepository */
        $imageRepository = ServiceContainer::getInstance()->get('image_repository');
        $images = $imageRepository->getUserImages($me['id']);

        return $this->render(['images' => $images]);
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
            $authService = ServiceContainer::getInstance()->get('auth_service');
            $me = $authService->getUser();

            /** @var ImageService $imageService */
            $imageService = ServiceContainer::getInstance()->get('image_service');
            $imageService->save($file, $me['id']);

            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('success', 'Фото добавлено');
        } catch (\Exception $e) {
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', $e->getMessage());
        }

        $request->redirect('/profile');
    }

    public function chooseMainPhoto(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');

        if (!$validator->isValid($request->post(), ['mainPhoto' => 'required'])) {
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', $validator->getFirstError());
            $request->redirect('/profile');
        }

        /** @var ImageRepository $imageRepository */
        $imageRepository = ServiceContainer::getInstance()->get('image_repository');
        $imageRepository->markImageAsMain((int) $request->post('mainPhoto'), $me['id']);

        /** @var NotificationService $notificationService */
        $notificationService = ServiceContainer::getInstance()->get('notification_service');
        $notificationService->set('success', 'Главное фото профиля обновлено');

        $request->redirect('/profile');
    }

    public function changePassword(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');

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
            $userRepository = ServiceContainer::getInstance()->get('user_repository');
            $userRepository->setNewPasswordHash(
                $me['id'], $authService->hashPassword($request->post('newPassword'))
            );

            if ($request->post('logoutEverywhere') === 'on') {
                /** @var TokenRepository $tokenRepository */
                $tokenRepository = ServiceContainer::getInstance()->get('token_repository');
                $tokenRepository->removeAllUserTokens($me['id']);

                $authService->setUpToken($me['id']);
            }

            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('success', 'Пароль изменен');
        } catch (\Exception $e) {
            /** @var NotificationService $notificationService */
            $notificationService = ServiceContainer::getInstance()->get('notification_service');
            $notificationService->set('error', $e->getMessage());
        }

        $request->redirect('/profile');
    }
}
