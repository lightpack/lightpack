<?php

namespace Lightpack\Cache\Drivers;

use Lightpack\Cache\DriverInterface;
use RuntimeException;

class File implements DriverInterface
{
    private $path;

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function get(string $key)
    {
        $file = $this->fileId($key);

		if(!file_exists($file)) {
            return null;
        }

        $contents = unserialize(file_get_contents($file));

        if($contents['expiry'] > time()) {
            return $contents['value'];
        }

        $this->delete($key); 
        return null;
    }

    public function set(string $key, string $value, int $ttl)
    {
        $file = $this->fileId($key);
        $value = serialize([
            'expire' => $ttl,
            'value' => $value,
        ]);

		file_put_contents($file, $value, LOCK_EX);
    }

    public function delete($key)
    {
        $file = $this->fileId($key);

        if(file_exists($file)) {
            unlink($file);
        }
    }

    public function flush()
    {
		array_map('unlink', glob($this->path . '*'));
    }

    private function setPath(string $path)
    {
        if(!is_dir($path) && !mkdir($path, 0775, true)) {
            throw new RuntimeException(
                sprintf("Unable to create file cache storage directory: %s", $path)
            );
        }

        if(!is_readable($path) || !is_writable($path)) {
            if(!chmod($path, 0755)) {
                throw new RuntimeException(
                    sprintf("File cache storage directory lacks read/write permission: %s", $path)
                );
            }
        }

        $this->path = realpath($path);
    }

    private function fileId($key)
    {
        return $this->path . DIRECTORY_SEPARATOR . sha1($key);
    }
}