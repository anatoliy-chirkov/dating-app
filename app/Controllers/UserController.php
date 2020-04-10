<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\GoogleGeoRepository;
use Repositories\ImageRepository;
use Repositories\UserRepository\UserRepository;
use Services\AuthService;

class UserController extends BaseController
{
    public function search(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $users = $userRepository->search(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('googleGeoId'),
            $request->get('page', 1)
        );
        $count = $userRepository->count(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('googleGeoId')
        );

        $pages = ceil($count / 20);

        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = ServiceContainer::getInstance()->get('google_geo_repository');

        return $this->render([
            'users' => $users,
            'sex' => $request->get('sex', []),
            'ageFrom' => $request->get('ageFrom', ''),
            'ageTo' => $request->get('ageTo', ''),
            'googleGeo' => $googleGeoRepository->getByIdArray($request->get('googleGeoId', [])),
            'page' => $request->get('page', 1),
            'pages' => $pages,
        ]);
    }

    public function getOne(Request $request, $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $user = $userRepository->getById($userId);

        $authService = ServiceContainer::getInstance()->get('auth_service');

        if ($authService->verifyCookieToken()) {
            /** @var AuthService $authService */
            $authService = ServiceContainer::getInstance()->get('auth_service');
            $me = $authService->getUser();
        }

        /** @var ImageRepository $imageRepository */
        $imageRepository = ServiceContainer::getInstance()->get('image_repository');
        $images = $imageRepository->getUserImages($userId);

        return $this->render([
            'user' => $user,
            'isMe' => isset($me) ? $me['id'] === $user['id'] : false,
            'images' => $images,
        ]);
    }
}
