<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\DotEnv;
use Shared\Core\Http\Request;
use Shared\Core\App;

class AuthController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['logout'];
    }

    public function login(Request $request)
    {
        if ($request->isPost()) {
            /** @var DotEnv $dotEnv */
            $dotEnv = App::get('env');

            if ($request->post('login') === $dotEnv->get('ADMIN_LOGIN')
                && $request->post('password') === $dotEnv->get('ADMIN_PASSWORD')
            ) {
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
