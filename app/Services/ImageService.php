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
        list($width, $height) = getimagesize($file->getServerPath());
        $this->imageRepository->create($userId, $file->getServerPath(), $file->getClientPath(), $width, $height);
        return $this->imageRepository->getByClientPath($file->getClientPath());
    }

    public function saveMainPhoto(File $file, int $userId)
    {
        [$userImgServerDir, $userImgClientDir] = $this->getUserImgDirs($userId);

        $file->saveTo($userImgServerDir, $userImgClientDir);
        list($width, $height) = getimagesize($file->getServerPath());
        $this->imageRepository->create($userId, $file->getServerPath(), $file->getClientPath(), $width, $height, true);
        return $this->imageRepository->getByClientPath($file->getClientPath());
    }

    public function deleteOne(int $imageId, int $userId)
    {
        $image = $this->imageRepository->getByIdAndUserId($imageId, $userId);

        if ($image === null) {
            throw new \Exception('This image already removed or it is not your image', 422);
        }

        unlink($image['serverPath']);

        $this->imageRepository->deleteOne($imageId, $userId);
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
