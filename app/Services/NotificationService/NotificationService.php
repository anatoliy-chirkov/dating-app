<?php

namespace Services\NotificationService;

class NotificationService
{
    private const SESSION_NOTIFICATION_KEY = '_notification';

    public function set(string $type, string $message)
    {
        $_SESSION[self::SESSION_NOTIFICATION_KEY] = serialize(Notification::create($type, $message));
    }

    public function flush()
    {
        if (!empty($_SESSION[self::SESSION_NOTIFICATION_KEY])) {
            $notification = unserialize($_SESSION[self::SESSION_NOTIFICATION_KEY]);
            unset($_SESSION[self::SESSION_NOTIFICATION_KEY]);
            return $notification;
        }

        return Notification::stub();
    }
}
