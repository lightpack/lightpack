<?php

namespace App\Filters\Auth;

use Lightpack\Http\Request;
use Lightpack\Http\Response;
use Lightpack\Filters\IFilter;

class ApiFilter implements IFilter
{
    public function before(Request $request)
    {
        $result = auth()->viaToken();

        if(!$result->isSuccess()) {
            return response()->setCode(401)->json([
                'error' => 'Unauthorized',
            ]);
        }
    }

    public function after(Request $request, Response $response): Response
    {
        return $response;
    }
}