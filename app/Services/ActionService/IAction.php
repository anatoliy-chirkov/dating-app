<?php

namespace Services\ActionService;

interface IAction
{
    public const
        SEND_MESSAGE = 'sendMessage',
        SEND_MESSAGE_TO_GIRL = 'sendMessageToGirl',
        SEE_VISITS = 'seeVisits',
        HIDE_VISIT = 'hideVisit',
        HIDE_ONLINE = 'hideOnline',
        BULK_MESSAGE = 'bulkMessage',
        PUT_MONEY = 'putMoney',
        NEW_DAY = 'newDay',
        REGISTRATION = 'registration'
    ;
}
