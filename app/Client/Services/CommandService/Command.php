<?php

namespace Client\Services\CommandService;

use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;

class Command
{
    public function raiseProfile(int $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $userRepository->raiseProfileInSearch($userId);
    }

    public function setInTop(int $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $userRepository->setTop($userId);
    }
}
