<?php

namespace Client\Services;

use Shared\Core\Http\File;
use Shared\Core\App;
use Client\Repositories\ImageRepository;

class ImageService
{
    /** @var ImageRepository */
    private $imageRepository;

    public function __construct()
    {
        $this->imageRepository = App::get('image');
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
        $usersImgDir = App::get('usersImgDir');

        return [
            $usersImgDir['server'] . '/' . $userId,
            $usersImgDir['client'] . '/' . $userId
        ];
    }
}
