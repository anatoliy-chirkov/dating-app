<?php

namespace Client\Services;

use Shared\Core\Http\UploadedFile;
use Shared\Core\App;
use Client\Repositories\AttachmentRepository;

class AttachmentService
{
    /** @var AttachmentRepository */
    private $attachmentRepository;

    public function __construct()
    {
        $this->attachmentRepository = App::get('attachment');
    }

    public function save(UploadedFile $file, int $chatId)
    {
        [$userImgServerDir, $userImgClientDir] = $this->getChatsImgDirs($chatId);

        $file->saveTo($userImgServerDir, $userImgClientDir);
        list($width, $height) = getimagesize($file->getServerPath());
        $this->attachmentRepository->create($chatId, $file->getServerPath(), $file->getClientPath(), $width, $height);

        return $this->attachmentRepository->getByClientPath($file->getClientPath());
    }

    private function getChatsImgDirs(int $chatId)
    {
        $chatsImgDir = App::getParam('chatsImgDir');

        return [
            $chatsImgDir['server'] . '/' . $chatId,
            $chatsImgDir['client'] . '/' . $chatId
        ];
    }
}
