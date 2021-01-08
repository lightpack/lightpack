<?php

namespace Lightpack\Pagination;

class Pagination
{
    public $total;
    public $perPage;
    public $currentPage;
    public $lastPage;
    public $path;
    
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
        $prev = $this->currentPage > 1 ? $this->currentPage - 1 : null;
        $next = $this->currentPage < $this->lastPage ? $this->currentPage + 1 : null;
        $prevLink = $prev ? "<a href=\"{$this->path}?page={$prev}\">Prev</a>" : ''; 
        $nextLink = $next ? "<a href=\"{$this->path}?page={$next}\">Next</a>" : ''; 
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
}