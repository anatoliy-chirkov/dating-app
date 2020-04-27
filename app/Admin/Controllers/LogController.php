<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\BillRepository;
use Admin\Repositories\LogRepository;
use Carbon\Carbon;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;

class LogController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all'];
    }

    public function all()
    {
        return $this->render();
    }

    public function search(Request $request)
    {
        $offset = (int) $request->get('start');
        $length = (int) $request->get('length');

        /** @var LogRepository $logRepository */
        $logRepository = ServiceContainer::getInstance()->get('log_repository');

        $logsCount = $logRepository->count();
        $logsHTML = [];

        foreach ($logRepository->search($offset, $length) as $log) {
            $logsHTML[] = [
                Carbon::parse($log['createdAt'])->locale('en')->isoFormat('D MMMM YYYY, HH:mm'),
                $log['code'],
                $log['message'],
                $log['uri'],
            ];
        }

        $this->renderJson([
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $logsCount,
            'recordsFiltered' => $logsCount,
            'data' => $logsHTML,
        ]);
    }
}
