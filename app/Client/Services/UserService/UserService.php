<?php

namespace Client\Services\UserService;

use Shared\Core\Http\UploadedFile;
use Shared\Core\App;
use Client\Repositories\GoalRepository;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\ImageService;

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
        $this->userRepository = App::get('user');
        $this->imageService = App::get('imageService');
        $this->userObjectFactory = new UserObjectFactory();
    }

    public function createUser(array $requestData, UploadedFile $mainUserPhoto = null)
    {
        $user = $this->userObjectFactory->build($requestData);

        if ($this->userRepository->isExist($user->email)) {
            throw new \Exception('User already exists');
        }

        $this->userRepository->createUser($user);
        $userId = $this->userRepository->getIdByEmail($user->email);

        if ($mainUserPhoto) {
            $this->imageService->saveMainPhoto($mainUserPhoto, $userId);
        }

        // save user goals
        /** @var GoalRepository $goalRepository */
        $goalRepository = App::get('goal');
        foreach ($requestData['goalId'] as $goalId) {
            $goalRepository->saveUserGoal($userId, $goalId);
        }

        return $this->userRepository->getByEmail($user->email);
    }
}
