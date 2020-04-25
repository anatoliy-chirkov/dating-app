<?php

namespace Services\CommandService;

use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;

class Command
{
    public function raiseProfile(int $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $userRepository->raiseProfileInSearch($userId);
    }

    public function setInTop(int $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $userRepository->setTop($userId);
    }
}
