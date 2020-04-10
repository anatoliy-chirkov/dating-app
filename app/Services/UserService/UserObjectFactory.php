<?php

namespace Services\UserService;

use Core\ServiceContainer;
use Repositories\UserRepository\Model\User;
use Services\AuthService;
use Services\GoogleGeoService\GoogleGeoService;

class UserObjectFactory
{
    public function build(array $data): User
    {
        $user = new User;
        array_walk($data, static function ($value, $key) use (&$user) {
            if ($key === 'password' || $key === 'repeatPassword') {
                if ($key === 'password') {
                    /** @var AuthService $authService */
                    $authService = ServiceContainer::getInstance()->get('auth_service');
                    $user->passwordHash = $authService->hashPassword($value);
                }
            } else if ($key === 'city') {
                /** @var GoogleGeoService $googleGeoService */
                $googleGeoService = ServiceContainer::getInstance()->get('google_geo_service');
                $user->googleGeoId = $googleGeoService->saveIfNotExistAndGetId($value);
            } else {
                $user->$key = empty($value) ? null : $value;
            }
        });

        return $user;
    }
}
