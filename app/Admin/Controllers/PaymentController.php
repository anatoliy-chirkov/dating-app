<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Core\Controllers\IProtected;

class PaymentController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['bills', 'purchases'];
    }

    public function bills()
    {
        return $this->render();
    }

    public function purchases()
    {
        return $this->render();
    }
}
