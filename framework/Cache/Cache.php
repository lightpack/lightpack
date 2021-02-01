<?php

namespace Lightpack\Cache;

class Cache
{
    private $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function has(string $key): bool
    {
        return $this->driver->has($key);
    }

    public function get(string $key)
    {
        return $this->driver->get($key);
    }

    public function set(string $key, string $value, int $duration)
    {
        return $this->driver->set($key, $value, $duration);
    }

    public function forget($key)
    {
        return $this->driver->forget($key);
    }

    public function forever(string $key, string $value)
    {
        $duration = time() + (60 * 60 * 24 * 365 * 5);
        return $this->driver->set($key, $value, $duration);
    }

    public function flush()
    {
        return $this->driver->flush();
    }
}