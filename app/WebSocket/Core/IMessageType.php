<?php

namespace WebSocket\Core;

interface IMessageType
{
    public const
        AUTHORIZE        = 'AUTHORIZE',
        MESSAGE          = 'MESSAGE',
        MESSAGE_WAS_READ = 'MESSAGE_WAS_READ',
        VISIT_PROFILE    = 'VISIT_PROFILE'
    ;
}
