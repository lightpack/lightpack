<?php

declare(strict_types=1);

namespace Lightpack\Routing;

use Lightpack\Http\Request ;
use Lightpack\Routing\Router;

class Dispatcher
{
    private $controller;
    private $action;
    private $params;
    private $request;
    private $router;

    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
        $this->throwExceptionIfRouteNotFound($router->meta());
        $this->controller = $router->namespace() . '\\' . $router->controller();
        $this->action = $router->action();
        $this->params = $router->params();
    }

    public function dispatch() 
    {
        if(! \class_exists($this->controller)) {
            throw new \Lightpack\Exceptions\ControllerNotFoundException(
                sprintf("Controller Not Found Exception: %s", $this->controller)
            );
        }

        if(! \method_exists($this->controller, $this->action)) {
            throw new \Lightpack\Exceptions\ActionNotFoundException(
                sprintf("Action Not Found Exception: %s@%s", $this->controller, $this->action)
            );
        }

        return (new $this->controller())->{$this->action}(...$this->params);
    }

    private function throwExceptionIfRouteNotFound()
    {
        if(!$this->router->meta()) {
            throw new \Lightpack\Exceptions\RouteNotFoundException(
                sprintf(
                    "No route registered for request: %s %s", 
                    $this->request->method(), 
                    $this->request->path()
                )
            );
        }
    }
}
