<?php

namespace Lightpack\Cache\Drivers;

use Lightpack\Cache\DriverInterface;

class File implements DriverInterface
{
    public function has(string $key): bool
    {
        return true;
    }

    public function get(string $key, $default = null)
    {

    }

    public function set(string $key, string $value, int $duration)
    {

    }

    public function forget($key)
    {

    }

    public function flush()
    {

    }
}