<?php

namespace Client\Services\LangService;

interface ILang
{
    public const
        EN = 'en',
        RU = 'ru'
    ;

    public const LIST = [
        self::EN,
        self::RU,
    ];
}
