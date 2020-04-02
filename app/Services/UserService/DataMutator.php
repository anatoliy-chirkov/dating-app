<?php

namespace Services\UserService;

use Core\ServiceContainer;
use Repositories\UserRepository\Model\User;
use Services\AuthService;

class DataMutator
{
    public function mutateToUser(array $data): User
    {
        $user = new User;
        array_walk($data, static function ($value, $key) use (&$user) {
            if ($key === 'password' || $key === 'repeatPassword') {
                if ($key === 'password') {
                    /** @var AuthService $authService */
                    $authService = ServiceContainer::getInstance()->get('auth_service');
                    $user->passwordHash = $authService->hashPassword($value);
                }
            } else {
                $user->$key = empty($value) ? null : $value;
            }
        });

        return $user;
    }
}
