<?php

namespace App\Filters;

use Lightpack\Http\Request;
use Lightpack\Http\Response;
use Lightpack\Filters\IFilter;

class CorsFilter implements IFilter
{
    public function before(Request $request)
    {
        if (app('request')->method() === 'OPTIONS') {
            return app('response')
                ->setCode(204)
                ->setMessage('No Content')
                ->setType('text/plain')
                ->setHeaders(app('config')->get('cors.headers'));
        }
    }

    public function after(Request $request, Response $response): Response
    {
        return $response->setHeaders(app('config')->get('cors.headers'));
    }
}
