<?php

namespace Lightpack\Pagination;

class Pagination
{
    private $total;
    private $perPage;
    private $currentPage;
    private $lastPage;
    private $path;
    private $allowedParams = [];
    
    public function __construct($total, $perPage = 10, $currentPage = null)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage ?? app('request')->get('page', 1);
        $this->lastPage = ceil($this->total / $this->perPage);
        $this->path = app('request')->fullpath();
    }

    public function links()
    {
        if($this->lastPage <= 1) {
            return '';
        }
        
        $prevLink = $this->prev();
        $nextLink = $this->next();
        $template = "Page {$this->currentPage} of {$this->lastPage} {$prevLink}  {$nextLink}";

        return $template;
    }

    public function withPath($path) {
        $this->path = url($path);
        return $this;
    }

    public function total()
    {
        return $this->total;
    }

    public function limit()
    {
        return $this->perPage;
    }
    
    public function offset()
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function count()
    {
        return $this->lastPage;
    }

    public function next()
    {
        $next = $this->currentPage < $this->lastPage ? $this->currentPage + 1 : null;
        
        if($next) {
            $query = $this->getQuery($next);
            return "<a href=\"{$this->path}?{$query}\">Next</a>";
        }
    }

    public function prev()
    {
        $prev = $this->currentPage > 1 ? $this->currentPage - 1 : null;
        
        if($prev) {
            $query = $this->getQuery($prev);
            return "<a href=\"{$this->path}?{$query}\">Prev</a>";
        }
    }

    public function only(array $params = [])
    {
        $this->allowedParams = $params;

        return $this;
    }

    private function getQuery(int $page): string
    {
        $params = $_GET; 
        $allowedParams = $this->allowedParams;

        if ($allowedParams) {
            $params = \array_filter($_GET, function ($key) use ($allowedParams) {
                return \in_array($key, $allowedParams);
            });
        }

        $params = array_merge($params, ['page' => $page]);

        return http_build_query($params);
    }
}