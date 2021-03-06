<?php

namespace Client\Controllers;

use Client\Controllers\Shared\SiteController;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\GoalRepository;
use Client\Repositories\GoogleGeoRepository;
use Client\Repositories\ImageRepository;
use Client\Repositories\UserRepository\UserRepository;
use Client\Repositories\VisitRepository;
use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;
use Client\Services\AuthService;
use Shared\Core\Router\Exceptions\PageNotFoundException;

class UserController extends SiteController
{
    public function search(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $users = $userRepository->search(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('googleGeoId'),
            $request->get('goalId'),
            $request->get('page', 1)
        );
        $count = $userRepository->count(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('googleGeoId'),
            $request->get('goalId')
        );

        $pages = ceil($count / 20);

        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = App::get('googleGeo');
        /** @var GoalRepository $goalRepository */
        $goalRepository = App::get('goal');

        return $this->render([
            'users' => $users,
            'sex' => $request->get('sex', []),
            'ageFrom' => $request->get('ageFrom', ''),
            'ageTo' => $request->get('ageTo', ''),
            'googleGeo' => $googleGeoRepository->getByIdArray($request->get('googleGeoId', [])),
            'goals' => $goalRepository->getAll(),
            'selectedGoalsId' => $request->get('goalId', []),
            'page' => $request->get('page', 1),
            'pages' => $pages,
        ]);
    }

    public function getOne(Request $request, $userId)
    {
        if ($this->isAuthorized) {
            if ($this->user['id'] !== $userId) {

                if (
                    !Action::hasRestrictedProduct(IAction::HIDE_VISIT)
                    || !Action::check(IAction::HIDE_VISIT, $this->user['id'])
                ) {
                    /** @var VisitRepository $visitRepository */
                    $visitRepository = App::get('visit');
                    $visitRepository->saveVisit($userId, $this->user['id']);
                }
            }
        }

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $user = $userRepository->getById($userId);

        if ($user === null) {
            throw new PageNotFoundException();
        }

        /** @var ImageRepository $imageRepository */
        $imageRepository = App::get('image');
        $images = $imageRepository->getUserImages($userId);

        /** @var GoalRepository $goalRepository */
        $goalRepository = App::get('goal');

        return $this->render([
            'user' => $user,
            'isMe' => isset($this->user) ? $this->user['id'] === $user['id'] : false,
            'images' => $images,
            'userGoals' => $goalRepository->getUserGoals($user['id']),
        ]);
    }
}
