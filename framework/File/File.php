<?php

namespace Lightpack\File;

use SplFileInfo;
use RuntimeException;
use FilesystemIterator;

class File
{
    public function info($path)
    {
        return new SplFileInfo($path);
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function get(string $path): ?string
    {
        if(!$this->exists($path)) {
            return null;
        }

        if(!is_readable($path)) {
            throw new RuntimeException(
                sprintf("Permission denied to read file contents: %s", $path)
            );
        }

        return file_get_contents($path);
    }

    public function put(string $path, string $contents, $flags = LOCK_EX)
    {
        return file_put_contents($path, $contents, $flags);
    }

    public function append(string $path, string $contents)
    {
        return $this->put($path, $contents, LOCK_EX | FILE_APPEND);
    }

    public function delete(string $path): bool
    {
        if($this->exists($path)) {
            return @unlink($path);
        }

        return false;
    }

    public function makeDir(string $path, int $mode = 0777): bool 
    {
        if(!is_dir($path)) {
            if(!mkdir($path, $mode, true)) {
                throw new RuntimeException(
                    sprintf("Unable to create directory: %s", $path)
                );
            }
        }

        return true;
    }

    public function emptyDir(string $path)
    {
        $this->removeDir($path, false);
    }

    public function moveDir(string $source, string $destination): bool
    {
        return $this->copyDir($source, $destination, true);
    }
    
    public function removeDir(string $path, bool $delete = true)
    {
        if(!is_dir($path)) {
            return;
        }

        foreach($this->getIterator($path) as $file) {
            if($file->isDir()) {
                $this->removeDir($file->getRealPath());
            } else {
                @unlink($file->getRealPath());
            }
        }

        if($delete) { 
            @rmdir($path);
        }
    }

    public function copyDir(string $source, string $destination, bool $delete = false): bool
    {
        if(!is_dir($source)) {
            return false;
        }

        $this->makeDir($destination);

        foreach($this->getIterator($source) as $file) {
            $destination = $destination . DIRECTORY_SEPARATOR . $file->getBasename();

            if($file->isDir()) {
                $success = $this->copyDir($file->getRealPath(), $destination, $delete);
                
                if(!$success) {
                    return false;
                }

                if($success && $delete) {
                    $this->removeDir($file->getRealPath());
                    continue;
                }
            } else {
                if(!copy($file->getRealPath(), $destination)) {
                    return false;
                }

                if($delete) {
                    @unlink($file->getRealPath());
                }
            }
        }

        if($delete) {
            $this->removeDir($source);
        }

        return true;
    }

    public function recent(string $path): ?SplFileInfo
    {
        $found = null;
        $timestamp = 0;
        
        foreach($this->getIterator($path) as $file) {
            if($timestamp < $file->getMTime()) {
                $found = $file;
                $timestamp = $file->getMTime();
            }
        }

        return $found;
    }

    private function getIterator(string $path): ?FilesystemIterator
    {
        if(!is_dir($path)) {
            return null;
        }

        return new FilesystemIterator($path);
    }
}