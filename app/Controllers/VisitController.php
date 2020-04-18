<?php

namespace Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Core\Controllers\BaseController;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\VisitRepository;
use Services\AuthService;

class VisitController extends BaseController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['see'];
    }

    public function see(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        /** @var VisitRepository $visitRepository */
        $visitRepository = ServiceContainer::getInstance()->get('visit_repository');
        $visitRepository->setAllVisitsHasSeen($me['id']);
        $visits = $visitRepository->getPageVisits($me['id'], $request->get('page', 1));
        $visitsCount = $visitRepository->getPageVisitsCount($me['id']);

        $pages = ceil($visitsCount / 14);

        foreach ($visits as &$visit) {
            $visit['time'] = Carbon::parse($visit['time'])->locale('ru')->diffForHumans(Carbon::now(), [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                'options' => Carbon::ONE_DAY_WORDS
            ]);
        }

        return $this->render([
            'visits' => $visits,
            'page' => $request->get('page', 1),
            'pages' => $pages,
        ]);
    }
}
