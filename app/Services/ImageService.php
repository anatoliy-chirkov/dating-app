<?php

namespace Services;

use Core\Http\File;
use Core\ServiceContainer;
use Repositories\ImageRepository;

class ImageService
{
    /** @var ImageRepository */
    private $imageRepository;

    public function __construct()
    {
        $this->imageRepository = ServiceContainer::getInstance()->get('image_repository');
    }

    public function save(File $file, int $userId)
    {
        [$userImgServerDir, $userImgClientDir] = $this->getUserImgDirs($userId);

        $file->saveTo($userImgServerDir, $userImgClientDir);
        $this->imageRepository->create($userId, $file->getServerPath(), $file->getClientPath());
        return $this->imageRepository->getByClientPath($file->getClientPath());
    }

    public function saveMainPhoto(File $file, int $userId)
    {
        [$userImgServerDir, $userImgClientDir] = $this->getUserImgDirs($userId);

        $file->saveTo($userImgServerDir, $userImgClientDir);
        $this->imageRepository->create($userId, $file->getServerPath(), $file->getClientPath(), true);
        return $this->imageRepository->getByClientPath($file->getClientPath());
    }

    private function getUserImgDirs(int $userId)
    {
        $usersImgDir = ServiceContainer::getInstance()->get('users_img_dir');

        return [
            $usersImgDir['server'] . '/' . $userId,
            $usersImgDir['client'] . '/' . $userId
        ];
    }
}
