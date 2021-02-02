<?php

namespace Lightpack\Cache;

interface DriverInterface
{
    public function has(string $key): bool;

    public function get(string $key);

    public function set(string $key, $value, int $lifetime);

    public function delete($key);

    public function flush();
}