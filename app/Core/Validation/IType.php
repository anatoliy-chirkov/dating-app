<?php

namespace Core\Validation;

interface IType
{
    public const
        EMAIL    = 'email',
        REQUIRED = 'required',
        XSS      = 'xss',
        LENGTH   = 'length'
    ;
}
