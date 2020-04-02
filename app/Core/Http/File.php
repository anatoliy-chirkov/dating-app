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

    private $path;

    public function __construct(array $file)
    {
        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->tmpName = $file['tmp_name'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->ext = pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function getSizeInKb()
    {
        return $this->size / 1000;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function saveTo(string $dir)
    {
        $path = $dir . '/' . $this->generateFileName();
        $isSaved = move_uploaded_file($this->tmpName, $path);

        if ($isSaved) {
            $this->path = $path;
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
