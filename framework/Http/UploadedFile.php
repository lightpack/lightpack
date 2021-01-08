<?php

namespace Lightpack\Http;

use Lightpack\Exceptions\FileUploadException;

class UploadedFile
{
    private $name;
    private $size;
    private $type;
    private $error;

    public function __construct($file)
    {
        $this->name = $file['name'];
        $this->size = $file['size'];
        $this->type = $file['type'];
        $this->error = $file['error'];    
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExtension() : string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function hasErrors(): bool
    {
        return UPLOAD_ERR_OK !== $this->error;
    }

    public function move(string $destination, string $name = null): void
    {
        if(!$this->hasErrors()) {
            throw new FileUploadException('Uploaded file has errors');
        }

        if(is_dir($destination)) {
            if(!is_writable($destination)) {
                throw new FileUploadException('Upload directory does not have sufficient write permission: ' . $destination);
            }
        } elseif(!mkdir($destination, 0777, true)) {
            throw new FileUploadException('Could not create upload directory: ' . $destination);
        }

        $this->processUpload($name ?? $this->name, $destination);
    }

    private function processUpload(string $name, string $destination): void
    {
        $targetPath = rtrim($destination, '\\/') . '/' . $name;
        $success = move_uploaded_file($name, $targetPath);

        if(!$success) {
            throw new FileUploadException('Could not upload the file.');
        }
    }
}