<?php

namespace App\Filters;

use Lightpack\Http\Request;
use Lightpack\Http\Response;
use Lightpack\Filters\IFilter;
use Lightpack\Exceptions\InvalidCsrfTokenException;

class CsrfFilter implements IFilter
{
    public function before(Request $request)
    {
        if(app('request')->isPost()) {
            if(app('session')->verifyToken() === false) {
                throw new InvalidCsrfTokenException('CSRF security token is invalid');
            }
        }
    }

    public function after(Request $request, Response $response): Response
    {
        return $response;
    } 
}