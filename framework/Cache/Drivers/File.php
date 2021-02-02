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

    public function set(string $key, string $value, int $duration)
    {
        $file = $this->fileId($key);
        $value = serialize([
            'expire' => time() + $duration,
            'value' => $value,
        ]);

		file_put_contents($file, $value, LOCK_EX);
    }

    public function delete($key)
    {
        @unlink($this->fileId($key));
    }

    public function flush()
    {
		array_map(
            function(string $file) {
                @unlink($file);
            }, 
            glob($this->path . '*')
        );
    }

    private function setPath(string $path)
    {
        if(!file_exists($path)) {
            throw new RuntimeException(
                sprintf("File cache storage path does not exist: %s", $path)
            );
        }

        if(!is_writable($path)) {
            throw new RuntimeException(
                sprintf("File cache storage path lacks write permission: %s", $path)
            );
        }

        $this->path = realpath($path);
    }

    private function fileId($key)
    {
        return $this->path . DIRECTORY_SEPARATOR . sha1($key);
    }
}