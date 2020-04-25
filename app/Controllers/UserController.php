<?php

namespace Controllers;

use Controllers\Shared\SiteController;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\GoogleGeoRepository;
use Repositories\ImageRepository;
use Repositories\UserRepository\UserRepository;
use Repositories\VisitRepository;
use Services\ActionService\Action;
use Services\ActionService\IAction;
use Services\AuthService;

class UserController extends SiteController
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
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');

        if ($authService->verifyCookieToken()) {
            $me = $authService->getUser();

            if ($me['id'] !== $userId) {

                if (
                    !Action::hasRestrictedProduct(IAction::HIDE_VISIT)
                    || !Action::check(IAction::HIDE_VISIT, $me['id'])
                ) {
                    /** @var VisitRepository $visitRepository */
                    $visitRepository = ServiceContainer::getInstance()->get('visit_repository');
                    $visitRepository->saveVisit($userId, $me['id']);
                }
            }
        }

        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $user = $userRepository->getById($userId);

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
