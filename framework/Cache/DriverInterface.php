<?php

namespace Lightpack\Cache;

interface DriverInterface
{
    public function has(string $key): bool;

    public function get(string $key, $default = null);

    public function set(string $key, string $value, int $duration);

    public function forget($key);

    public function flush();
}