<?php

namespace Admin\Controllers\Shared;

use Shared\Core\Controllers\BaseController;

abstract class AdminController extends BaseController
{
    private const NAMESPACE = 'Admin';

    protected function isAuthorized(): bool
    {
        return $_SESSION['loggedIn'] === true;
    }

    protected function getViewsPath(): string
    {
        return APP_PATH . '/' . self::NAMESPACE . '/' . self::VIEWS_FOLDER;
    }

    protected function getLayoutName(): string
    {
        return $this->isAuthorized()
            ? parent::getLayoutName()
            : 'notAuthorizedLayout.php'
        ;
    }
}
