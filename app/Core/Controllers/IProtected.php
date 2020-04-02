<?php

namespace Core\Controllers;

interface IProtected extends ICatchMethods
{
    public function getProtectedMethods();
}
