<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Core\Http\Request;

class AuthController extends AdminController
{
    private const
        LOGIN = 'admin',
        PASSWORD = '123'
    ;

    public function login(Request $request)
    {
        if ($request->isPost()) {
            if ($request->post('login') === self::LOGIN && $request->post('password') === self::PASSWORD) {
                $_SESSION['loggedIn'] = true;
            }

            $request->redirect('/');
        }

        return $this->render();
    }

    public function logout(Request $request)
    {
        $_SESSION['loggedIn'] = false;
        $request->redirect('/');
    }
}
