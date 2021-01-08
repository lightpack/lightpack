<?php

declare(strict_types=1);

namespace Lightpack\Http;

class Request
{
    private $files;
    private $basepath;
    private static $verbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct(string $basepath = null)
    {
        $this->basepath = $basepath ?? dirname($_SERVER['SCRIPT_NAME']);
        $this->files = new Files($_FILES ?? []);
    }

    public function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function query(): string
    {
        return explode('?', $this->uri())[1] ?? '';
    }

    public function basepath(): string
    {
        return $this->basepath;
    }

    public function fullpath(): string
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        return '/' . trim($path, '/');
    }

    public function path(): string
    {
        $path = substr(
            $this->fullpath(),
            strlen($this->basepath)
        );

        return '/' . trim($path, '/');
    }

    public function segments(int $index = null)
    {
        $segments = explode('/', trim($this->path(), '/'));

        if($index === null) {
            return $segments;
        }

        return $segments[$index] ?? null;
    }

    public function get(?string $key = null, ?string $default = null)
    {
        if(null === $key) {
            return $_GET;
        }

        return $_GET[$key] ?? $default;
    }

    public function post(?string $key = null, ?string $default = null)
    {
        if(null === $key) {
            return $_POST;
        }

        return $_POST[$key] ?? $default;
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function body(): string
    {
        return file_get_contents('php://input') ?? null;
    }

    public function json(): array
    {
        $json = json_decode($this->body(), true);

        if($json === null) {
            throw new \RuntimeException('Error decoding request body as JSON');
        }

        return $json;
    }

    public function files()
    {
        return $this->files;
    }

    public function file(string $key)
    {
        return $this->files->get($key);
    }

    public function hasFile(string $key)
    {
        return $this->files->has($key);
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isPut()
    {
        return $this->method() === 'PUT';
    }

    public function isPatch()
    {
        return $this->method() === 'PATCH';
    }

    public function isDelete()
    {
        return $this->method() === 'DELETE';
    }

    public function isValid()
    {
        return in_array($this->method(), self::$verbs);
    }

    public function isAjax()
    {
        return ($_SERVER['X-Requested-With'] ?? null)  === 'XMLHttpRequest';
    }

    public function isJson()
    {
        return false !== stripos($this->format(), 'json');
    }

    public function isSecure()
    {
        return $this->scheme() === 'https';
    }

    public function scheme()
    {
        if (
            (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') || 
            (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443'))) 
        {
            return 'https';
        }

        return 'http';
    }

    public function host()
    {
        return $_SERVER['HTTP_HOST'] ?? getenv('HTTP_HOST');
    }

    public function protocol()
    {
        return $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    }

    public function format()
    {
        return $_SERVER['CONTENT_TYPE'] ?? null;
    }

    public function verbs()
    {
        return self::$verbs;
    }
}
