<?php

namespace Core\Http;

class File
{
    /** @var string Original file name on user PC */
    private $name;
    /** @var string */
    private $type;
    /** @var string File temporary saved with this name */
    private $tmpName;
    /** @var int Error code */
    private $error;
    /** @var int Bytes */
    private $size;
    /** @var File extension */
    private $ext;

    private $serverPath;
    private $clientPath;

    public function __construct(array $file)
    {
        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->tmpName = $file['tmp_name'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->ext = pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function isNotLoaded()
    {
        return $this->error === 4;
    }

    public function getSizeInKb()
    {
        return $this->size / 1000;
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

        $serverPath = $serverDir . '/' . $fileName;
        $clientPath = $clientDir . '/' . $fileName;

        if (!file_exists($serverDir)) {
            mkdir($serverDir, 0775, true);
        }

        $isSaved = move_uploaded_file($this->tmpName, $serverPath);

        if ($isSaved) {
            $this->serverPath = $serverPath;
            $this->clientPath = $clientPath;
        }
    }

    public function getExtension()
    {
        return $this->ext;
    }

    private function generateFileName()
    {
        return hash('md5', $this->name . time()) . '.' . $this->ext;
    }
}
