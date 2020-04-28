<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Shared\Core\Controllers\IProtected;

class GoalController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all', 'create', 'edit'];
    }

    public function all()
    {

    }

    public function create()
    {

    }

    public function edit()
    {

    }
}
