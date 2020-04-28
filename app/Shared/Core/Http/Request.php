<?php

namespace Shared\Core\Http;

class Request
{
    private $uri;
    private $method;
    private $postData;
    private $getData;
    private $rawData;

    public function __construct()
    {
        $requestUriArr = explode('?', $_SERVER['REQUEST_URI'], 2);

        $this->uri      = $requestUriArr[0];
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->postData = $_POST;
        $this->getData  = $_GET;
        $this->rawData  = file_get_contents("php://input");
    }

    public function redirect(string $to, $type = 'internal')
    {
        if ($type === 'internal') {
            $url = "http://{$_SERVER['HTTP_HOST']}{$to}";
        } else {
            $url = $to;
        }

        header("Location: {$url}");
        die;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function isPost()
    {
        return $this->method === IMethod::POST;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->postData;
        }

        return empty($this->postData[$key]) ? $default : $this->postData[$key];
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->getData;
        }

        return empty($this->getData[$key]) ? $default : $this->getData[$key];
    }

    public function file(string $key): ?File
    {
        if (empty($_FILES[$key])) {
            return null;
        }

        $file = is_array($_FILES[$key][0]) ? new File($_FILES[$key][0]) : new File($_FILES[$key]);

        return $file->isNotLoaded() ? null : $file;
    }

    public function decodedJson(string $key = null)
    {
        $decoded = json_decode($this->rawData);

        if (!$key) {
            return $decoded;
        }

        return isset($decoded->$key) ? $decoded->$key : null;
    }
}
