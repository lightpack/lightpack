<?php

namespace Framework\Filters;

use Framework\Http\Request;
use Framework\Http\Response;

class Filter
{
    private $filters = [];
    private $request;
    private $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = &$request;
        $this->setResponse($response);
    }

    public function setResponse(Response $response) {
        $this->response = &$response;
    }

    public function register(string $route, IFilter $filter): void
    {
        $this->filters[$route] = $this->filters[$route] ?? [];
        $this->filters[$route][] = $filter;
    }

    public function processBeforeFilters(string $route)
    {
        foreach(($this->filters[$route] ?? []) as $filter) {
            $result = $filter->before($this->request);

            if($result instanceof Response) {
                return $result;
            }
        }
    }

    public function processAfterFilters(string $route)
    {
        foreach(($this->filters[$route] ?? []) as $filter) {
            $result = $filter->after($this->request,  $this->response);

            if($result instanceof Response) {
                $this->response = $result;
            }
        }

        return $this->response;
    }
}