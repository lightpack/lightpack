<?php

namespace Lightpack\File;

use DateTime;
use SplFileInfo;
use RuntimeException;
use FilesystemIterator;

class File
{
    public function info($path): ?SplFileInfo
    {
        if(!is_file($path)) {
            return null;
        }
        
        return new SplFileInfo($path);
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function isDir(string $path): bool
    {
        return is_dir($path);
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

    public function delete(string $path): bool
    {
        if($this->exists($path)) {
            return @unlink($path);
        }

        return false;
    }

    public function append(string $path, string $contents)
    {
        return $this->put($path, $contents, LOCK_EX | FILE_APPEND);
    }

    public function copy(string $source, string $destination): bool
    {
        if($this->exists($source)) {
            return copy($source, $destination);
        }

        return false;
    }

    public function rename(string $old, string $new): bool
    {
        if($this->copy($old, $new)) {
            @unlink($old);
        }

        return false;
    }

    public function move(string $source, string $destination): bool
    {
        return $this->rename($source, $destination);
    }

    public function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function size(string $path, string $unit = null)
    {
        $bytes = filesize($path);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if($unit = null || !array_key_exists($unit, $units)) {
            return $bytes;
        }   
        
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow]; 
    }

    public function lastModified(string $path, string $dateFormat = null) {
        $modified = filemtime($path);

        if($dateFormat) {
            $date = new DateTime($modified);
            $modified = $date->format($dateFormat);
        }

        return $modified;
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
            $from = $file->getRealPath();
            $to = $destination . DIRECTORY_SEPARATOR . $file->getBasename();

            if($file->isDir()) {
                if(!$this->copyDir($from, $to, $delete)) {
                    return false;
                }

                if($delete) {
                    $this->removeDir($from);
                }
            } else {
                if(!copy($from, $to)) {
                    return false;
                }

                if($delete) {
                    @unlink($from);
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

    public function traverse(string $path): ?array
    {
        if(!$this->isDir($path)) {
            return null;
        }

        $files = [];

        foreach($this->getIterator($path) as $file) {
            $files[$file->getFilename()] = $file;
        }

        return $files;
    }

    private function getIterator(string $path): ?FilesystemIterator
    {
        if(!is_dir($path)) {
            return null;
        }

        return new FilesystemIterator($path);
    }
}