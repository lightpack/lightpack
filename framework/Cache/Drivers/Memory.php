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

    public function get(string $key)
    {
        return $this->store[$key] ?? null;
    }

    public function set(string $key, string $value, int $lifetime)
    {
        $this->store[$key] = $value;
    }

    public function delete($key)
    {
        unset($this->store[$key]);
    }

    public function flush()
    {
        $this->store = [];
    }
}