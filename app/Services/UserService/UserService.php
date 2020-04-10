<?php

namespace Services\UserService;

use Core\Http\File;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;
use Services\ImageService;

class UserService
{
    /** @var UserRepository */
    private $userRepository;
    /** @var ImageService */
    private $imageService;
    /** @var UserObjectFactory */
    private $userObjectFactory;

    public function __construct()
    {
        $this->userRepository = ServiceContainer::getInstance()->get('user_repository');
        $this->imageService = ServiceContainer::getInstance()->get('image_service');
        $this->userObjectFactory = new UserObjectFactory();
    }

    public function createUser(array $requestData, File $mainUserPhoto = null)
    {
        $user = $this->userObjectFactory->build($requestData);

        if ($this->userRepository->isExist($user->email)) {
            throw new \Exception('User already exists');
        }

        $this->userRepository->createUser($user);

        if ($mainUserPhoto) {
            $userId = $this->userRepository->getIdByEmail($user->email);
            $this->imageService->saveMainPhoto($mainUserPhoto, $userId);
        }

        return $this->userRepository->getByEmail($user->email);
    }
}
