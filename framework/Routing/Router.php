<?php

namespace Framework\Routing;

use Framework\Http\Request;
use Framework\Routing\Route;

/**
 * Responsible to determine the correct controller/action to execute.
 */
class Router
{
    private $route;
    private $routeMeta = [];

    public function __construct(Request $request, Route $route)
    {
        $this->route = $route;
        $this->parse($request->path());
    }

    public function controller(): string
    {
        return $this->routeMeta['controller'];
    }

    public function action(): string
    {
        return $this->routeMeta['action'];
    }

    public function params(): array
    {
        return $this->routeMeta['params'];
    }
    
    public function path(): string
    {
        return $this->routeMeta['path'];
    }

    public function route(): string
    {
        return $this->routeMeta['route'];
    }

    public function filters(): array
    {
        return $this->routeMeta['filters'];
    }

    public function method(): string
    {
        return $this->routeMeta['method'];
    }

    public function namespace(): string
    {
        return $this->routeMeta['namespace'];
    }

    public function meta(): array
    {
        return $this->routeMeta;
    }

    public function parse(string $path): void
    {
        $routeMeta = [];

        if ($this->route->matches($path, $routeMeta)) {
            $this->routeMeta = $routeMeta;
        }
    }
}
