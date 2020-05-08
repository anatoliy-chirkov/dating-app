<?php

namespace Client\Services\UserService;

use Shared\Core\App;
use Client\Repositories\UserRepository\Model\User;
use Client\Services\AuthService;
use Client\Services\GoogleGeoService\GoogleGeoService;

class UserObjectFactory
{
    public function build(array $data): User
    {
        $user = new User;
        array_walk($data, static function ($value, $key) use (&$user) {
            if ($key === 'password' || $key === 'repeatPassword') {
                if ($key === 'password') {
                    /** @var AuthService $authService */
                    $authService = App::get('authService');
                    $user->passwordHash = $authService->hashPassword($value);
                }
            } else if ($key === 'city') {
                /** @var GoogleGeoService $googleGeoService */
                $googleGeoService = App::get('googleGeoService');
                $user->googleGeoId = $googleGeoService->saveIfNotExistAndGetId($value);
            } else {
                $user->$key = empty($value) ? null : $value;
            }
        });

        return $user;
    }
}
