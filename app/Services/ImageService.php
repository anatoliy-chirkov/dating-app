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
        $file->saveTo($this->getUserImgDir($userId));
        $this->imageRepository->create($userId, $file->getPath());
        return $this->imageRepository->getByPath($file->getPath());
    }

    public function saveMainPhoto(File $file, int $userId)
    {
        $file->saveTo($this->getUserImgDir($userId));
        $this->imageRepository->create($userId, $file->getPath(), true);
        return $this->imageRepository->getByPath($file->getPath());
    }

    private function getUserImgDir(int $userId)
    {
        $usersImgDir = ServiceContainer::getInstance()->get('users_img_dir');
        return $usersImgDir . '/' . $userId;
    }
}
