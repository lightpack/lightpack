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

    public function set(string $key, string $value, int $minutes)
    {
        $ttl = time() + ($minutes * 60);
        return $this->driver->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->driver->delete($key);
    }

    public function forever(string $key, string $value)
    {
        $lifetime = time() + (60 * 60 * 24 * 365 * 5);
        return $this->driver->set($key, $value, $lifetime);
    }

    public function flush()
    {
        return $this->driver->flush();
    }
}