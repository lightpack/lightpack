<?php

namespace Framework\Container;

use Closure;

class Container
{
    private $services = [];

    public function has(string $id): bool 
    {
        return array_key_exists($id, $this->services);
    }

    public function get(string $id): ?object
    {   
        $this->throwExceptionIfServiceNotFound($id);
        $service = $this->services[$id];

        if($service instanceof Closure) {
            return $service($this);
        }

        return $service;
    }

    public function factory(string $id, callable $cb): void
    {
        $this->services[$id] = $cb;
    }

    public function register(string $id, callable $cb): void
    {
        $this->services[$id] = function() use($cb) {
            static $instance;

            if($instance == null) {
                $instance = $cb($this);
            }

            return $instance;
        };
    }
    
    private function throwExceptionIfServiceNotFound(string $id): void
    {
        if (!$this->has($id)) {
            throw new \Framework\Exceptions\ServiceNotFoundException(
                sprintf(
                    'Service `%s` is not registered',
                    $id
                )
            );
        }
    }
}