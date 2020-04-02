<?php

namespace Services\NotificationService;

class Notification
{
    public $type;
    public $message;

    public static function create(string $type, string $message)
    {
        $notification = new self;

        $notification->type = $type;
        $notification->message = $message;

        return $notification;
    }

    public static function stub()
    {
        return new self;
    }

    public function isset()
    {
        return isset($this->message);
    }
}
