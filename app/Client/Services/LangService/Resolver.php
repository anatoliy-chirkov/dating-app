<?php

namespace Client\Services\LangService;

class Resolver
{
    public static function getLang(): string
    {
        return isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'ru';
    }

    public static function setLang(string $lang)
    {
        setcookie('lang', $lang);
    }
}
