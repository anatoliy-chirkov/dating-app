<?php

namespace Shared\Core\Controllers;

interface IProtected extends ICatchMethods
{
    public function getProtectedMethods();
}
