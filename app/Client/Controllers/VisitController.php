<?php

namespace Client\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Client\Controllers\Shared\SiteController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\VisitRepository;
use Client\Services\AuthService;

class VisitController extends SiteController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['see'];
    }

    public function see(Request $request)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $me = $authService->getUser();

        /** @var VisitRepository $visitRepository */
        $visitRepository = App::get('visit');
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
