<?php

namespace App\Filters;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Filters\IFilter;
use Framework\Exceptions\InvalidCsrfTokenException;

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

    } 
}