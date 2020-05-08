<?php

namespace Shared\Core\Http;

use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadedFile extends SymfonyUploadedFile
{
    private $serverPath;
    private $clientPath;

    public function __construct(array $file)
    {
        parent::__construct($file['tmp_name'], $file['name'], $file['type'], $file['error']);
    }

    public function getServerPath()
    {
        return $this->serverPath;
    }

    public function getClientPath()
    {
        return $this->clientPath;
    }

    public function saveTo(string $serverDir, string $clientDir)
    {
        $fileName = $this->generateFileName();

        $serverPath = $this->move($serverDir, $fileName);

        $this->serverPath = $serverPath;
        $this->clientPath = $clientDir . '/' . $fileName;
    }

    private function generateFileName()
    {
        return hash('md5', $this->getClientOriginalName() . time())
            . '.' . $this->getClientOriginalExtension();
    }
}
