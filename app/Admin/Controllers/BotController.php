<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Shared\Core\Controllers\IProtected;

class BotController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all'];
    }

    public function all()
    {
        return $this->render();
    }
}
