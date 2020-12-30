<?php

namespace Framework\Filters;

use Framework\Http\Request;
use Framework\Http\Response;

interface IFilter
{
    public function before(Request $request);
    public function after(Request $request, Response $response): Response; 
}