<?php

namespace Shared\Core;

class HttpErrorHandler
{
    public static function handle(\Exception $e)
    {
        switch ($e->getCode()) {
            case 403:
                header("Location: http://{$_SERVER['HTTP_HOST']}/login");
                break;
            case 500:
                /** @var \Repositories\LogRepository $logRepository */
                $logRepository = App::get('log');
                $logRepository->log($_SERVER['REQUEST_URI'], $e->getCode(), $e->getMessage());
                header("Location: http://{$_SERVER['HTTP_HOST']}{$_SERVER['HTTP_REFERER']}");
                break;
            case 0:
                self::screen(500, $e->getMessage());
                break;
            default:
                header("HTTP/1.0 {$e->getCode()}");
                self::screen($e->getCode(), $e->getMessage());
        }
    }

    private static function screen(int $code, string $message)
    {
        echo <<<HTML
<html>
    <header>
        <title>{$message}</title>
    </header>
    <body style="text-align: center;margin-top: 46px;">
        <h1>{$code}</h1>
        <div>{$message}</div>
    </body>
</html>
HTML;
    }
}
