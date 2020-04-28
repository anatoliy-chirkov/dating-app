<?php

namespace Client\Services;

use Shared\Core\DotEnv;
use Shared\Core\App;
use Client\Repositories\TokenRepository;
use Client\Repositories\UserRepository\UserRepository;

class AuthService
{
    private const
        COOKIE_TOKEN_KEY       = '_token',
        COOKIE_EXPIRATION_TIME = 3600
    ;

    /** @var TokenRepository */
    private $tokenRepository;
    /** @var UserRepository */
    private $userRepository;

    public function __construct()
    {
        $this->tokenRepository = App::get('token');
        $this->userRepository = App::get('user');
    }

    public function getUser()
    {
        $token = $this->getTokenFromCookie();
        return $this->userRepository->getUserByToken($token);
    }

    public function checkPasswordsByUserId(int $userId, string $password)
    {
        $user = $this->userRepository->getById($userId);

        if ($user === null) {
            throw new \Exception('Пользовтаель не найден', 1000);
        }

        return $this->verifyPassword($password, $user['passwordHash']);
    }

    public function checkEmailAndPassword(string $email, string $password)
    {
        $user = $this->userRepository->getByEmail($email);

        if ($user === null) {
            throw new \Exception('Пользовтаель не найден', 1000);
        }

        return $this->verifyPassword($password, $user['passwordHash']);
    }

    public function verifyPassword(string $candidate, string $passwordHash)
    {
        return $passwordHash === $this->hashPassword($candidate);
    }

    public function hashPassword(string $password)
    {
        return hash('sha256', $password);
    }

    public function setUpToken(int $userId)
    {
        $token = $this->generateToken($userId);
        $this->setTokenToCookie($token);
        $this->tokenRepository->save($token, $userId);
    }

    public function verifyCookieToken()
    {
        $token = $this->getTokenFromCookie();

        if (!$token) {
            return false;
        }

        return !empty($this->tokenRepository->find($token));
    }

    public function removeToken()
    {
        $token = $this->getTokenFromCookie();
        $this->setTokenToCookie(null);
        $this->tokenRepository->removeToken($token);
    }

    private function getTokenFromCookie()
    {
        return $_COOKIE[self::COOKIE_TOKEN_KEY];
    }

    private function setTokenToCookie($value)
    {
        setcookie(self::COOKIE_TOKEN_KEY, $value, time() + self::COOKIE_EXPIRATION_TIME);
    }

    private function generateToken(int $userId)
    {
        $tokenSecret = App::get('env')->get('TOKEN_SECRET');

        return hash('md5', $userId . time() . $tokenSecret);
    }
}
