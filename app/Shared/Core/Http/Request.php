<?php

namespace Shared\Core\Http;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\ServerBag;

class Request extends SymfonyRequest
{
    public function initialize(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->request = new ParameterBag($_POST);
        $this->query = new ParameterBag($_GET);
        $this->cookies = new ParameterBag($_COOKIE);
        $this->files = new FileBag($_FILES);
        $this->server = new ServerBag($_SERVER);
        $this->headers = new HeaderBag($this->server->getHeaders());
        $this->content = file_get_contents("php://input");
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
        return $this->getRequestUri();
    }

    public function getMethod()
    {
        return $this->getRealMethod();
    }

    public function isPost()
    {
        return $this->isMethod(self::METHOD_POST);
    }

    public function all()
    {
        return array_merge($this->files->all(), $this->request->all(), $this->query->all());
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->all();
        }

        return $this->request->get($key, $default);
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query->all();
        }

        return $this->query->get($key, $default);
    }

    public function file(string $key): ?UploadedFile
    {
        return $this->files->get($key);
    }

    public function decodedJson(string $key = null)
    {
        $decoded = @json_decode($this->content);

        if (!$key) {
            return $decoded;
        }

        return isset($decoded->$key) ? $decoded->$key : null;
    }
}
