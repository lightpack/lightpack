<?php

namespace Lightpack\Cache\Drivers;

use Lightpack\Cache\DriverInterface;

class Memory implements DriverInterface
{
    private $store = [];

    public function has(string $key): bool
    {
        return isset($this->store[$key]);
    }

    public function get(string $key, $default = null)
    {
        return $this->store[$key] ?? $default;
    }

    public function set(string $key, string $value, int $duration)
    {
        $this->store[$key] = $value;
    }

    public function forget($key)
    {
        unset($this->store[$key]);
    }

    public function flush()
    {
        $this->store = [];
    }
}