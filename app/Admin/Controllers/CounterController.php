<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Admin\Repositories\CounterRepository;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;

class CounterController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        ['all', 'create'];
    }

    public function all()
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');

        return $this->render([
            'counters' => $counterRepository->counters(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost()) {
            $request->redirect('/products/counters');
        }

        return $this->render();
    }
}
