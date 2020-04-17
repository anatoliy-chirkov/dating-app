<?php

namespace Services;

use Core\Http\File;
use Core\ServiceContainer;
use Repositories\AttachmentRepository;
use Repositories\ImageRepository;

class AttachmentService
{
    /** @var AttachmentRepository */
    private $attachmentRepository;

    public function __construct()
    {
        $this->attachmentRepository = ServiceContainer::getInstance()->get('attachment_repository');
    }

    public function save(File $file, int $chatId)
    {
        [$userImgServerDir, $userImgClientDir] = $this->getChatsImgDirs($chatId);

        $file->saveTo($userImgServerDir, $userImgClientDir);
        list($width, $height) = getimagesize($file->getServerPath());
        $this->attachmentRepository->create($chatId, $file->getServerPath(), $file->getClientPath(), $width, $height);

        return $this->attachmentRepository->getByClientPath($file->getClientPath());
    }

    private function getChatsImgDirs(int $chatId)
    {
        $chatsImgDir = ServiceContainer::getInstance()->get('chats_img_dir');

        return [
            $chatsImgDir['server'] . '/' . $chatId,
            $chatsImgDir['client'] . '/' . $chatId
        ];
    }
}
