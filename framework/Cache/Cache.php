<?php

namespace Lightpack\Cache;

class Cache
{
    private $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
}