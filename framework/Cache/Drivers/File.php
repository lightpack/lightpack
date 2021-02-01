<?php

namespace Lightpack\Cache\Drivers;

use Lightpack\Cache\DriverInterface;

class File implements DriverInterface
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function get(string $key)
    {
        $filepath = $this->path . $key;

		if(!file_exists($filepath)) {
            return null;
        }

        $contents = file_get_contents($filepath);
        $expiry = substr($contents, 0, 10);

        if($expiry > time()) {
            return unserialize(substr($contents, 10));
        }

        $this->forget($key); 
        return null;
    }

    public function set(string $key, string $value, int $duration)
    {
        $value = $duration . serialize($value);
		file_put_contents($this->path . $key, $value, LOCK_EX);
    }

    public function forget($key)
    {
        @unlink($this->path . $key);
    }

    public function flush()
    {
		array_map(
            function(string $filepath) {
                @unlink($filepath);
            }, 
            glob($this->path . '*')
        );
    }
}