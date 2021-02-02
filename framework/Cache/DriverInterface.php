<?php

namespace Lightpack\Cache;

interface DriverInterface
{
    public function has(string $key): bool;

    public function get(string $key);

    public function set(string $key, string $value, int $ttl);

    public function delete($key);

    public function flush();
}