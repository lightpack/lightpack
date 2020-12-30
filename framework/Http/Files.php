<?php

namespace Framework\Http;

class Files
{
    private $files;

    public function __construct(array $files = [])
    {
        foreach($files as $file) {
            $this->files[$file['name']] = new UploadedFile($file);
        }
    }

    public function get(string $key = null)
    {
        if($key === null) {
            return $this->files;
        }

        return $this->files[$key] ?? null;
    }

    public function has(string $key) {
        return isset($this->files[$key]);
    }
}