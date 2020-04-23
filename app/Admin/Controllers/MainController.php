<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Core\Http\Request;

class MainController extends AdminController
{
    public function main(Request $request)
    {
        if (!$this->isAuthorized()) {
            $request->redirect('/login');
        }

        return $this->render();
    }
}
