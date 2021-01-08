<?php

declare(strict_types=1);

namespace Lightpack\Routing;

class Route
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];
    private $placeholders = [
        ':any' => '(.*)',
        ':seg' => '([^/]+)',
        ':num' => '([0-9]+)',
        ':slug' => '([a-zA-Z0-9-]+)',
        ':alpha' => '([a-zA-Z]+)',
        ':alnum' => '([a-zA-Z0-9]+)',
    ];
    private $options = [
        'prefix' => '',
        'namespace' => '',
        'filters' => [],
    ];
    private $request;

    public function __construct(\Lightpack\Http\Request $request)
    {
        $this->request = $request;
    }

    public function get(string $path, string $handler, array $filters = []): void
    {
        $this->add('GET', $this->options['prefix'] . $path, $handler, $filters);
    }

    public function post(string $path, string $handler, array $filters = []): void
    {
        $this->add('POST', $this->options['prefix'] . $path, $handler, $filters);
    }

    public function put(string $path, string $handler, array $filters = []): void
    {
        $this->add('PUT', $this->options['prefix'] . $path, $handler, $filters);
    }

    public function patch(string $path, string $handler, array $filters = []): void
    {
        $this->add('PATCH', $this->options['prefix'] . $path, $handler, $filters);
    }

    public function delete(string $path, string $handler, array $filters = []): void
    {
        $this->add('DELETE', $this->options['prefix'] . $path, $handler, $filters);
    }

    public function paths(string $method): array
    {
        return $this->routes[$method] ?? [];
    }

    public function group(array $options, callable $callback): void {
        $oldOptions = $this->options;
        $this->options = \array_merge($oldOptions, $options);
        $this->options['prefix'] = $oldOptions['prefix'] . $this->options['prefix'];
        $callback($this);
        $this->options = $oldOptions;
    }

    public function map(array $verbs, string $route, string $handler): void {
        foreach($verbs as $verb) {
            if(false === \array_key_exists($verb, $this->routes)) {
                throw new \Exception('Unsupported HTTP request method: ' . $verb);
            }
            
            $this->{$verb}($route, $handler);
        }
    }

    public function any(string $path, string $handler, array $filters = []): void {
        $verbs = \array_keys($this->routes);

        foreach($verbs as $verb) {
            $this->{$verb}($path, $handler, $filters);
        }
    }

    public function matches(string $path, array &$meta = []): bool
    {
        $routes = $this->getRoutesForCurrentRequest();

        foreach ($routes as $route) {
            if (preg_match('@^' . $this->regex($route) . '$@', $path, $matches)) {
                \array_shift($matches);
                $meta = $this->routes[$this->request->method()][$route];
                $meta['params'] = $matches;
                $meta['method'] = $this->request->method();
                $meta['route'] = $route;
                $meta['path'] = $path;

                return true;
            }
        }

        return false;
    }

    private function add(string $method, string $path, string $handler, array $filters = []): void
    {
        
        if (trim($path) === '') {
            throw new \Exception('Empty route path');
        }
        
        $this->routes[$method][$path] = $this->normalizeHandler($handler);
        $this->routes[$method][$path]['filters'] = array_unique(array_merge($this->options['filters'], $filters));
    }

    private function normalizeHandler(string $handler): array
    {
        $parts = explode('@', $handler);

        if (count($parts) !== 2) {
            throw new \Exception('Invalid route path configuration: ' . $handler);
        }

        return ['namespace' => $this->options['namespace'], 'controller' => $parts[0], 'action' => $parts[1]];
    }

    private function regex(string $path): string
    {
        $search = \array_keys($this->placeholders);
        $replace = \array_values($this->placeholders);

        return str_replace($search, $replace, $path);
    }

    private function getRoutesForCurrentRequest()
    {
        $requestMethod = $this->request->method();
        $routes = $this->routes[$requestMethod] ?? [];
        return \array_keys($routes);
    }
}
