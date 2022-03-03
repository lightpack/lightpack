<?php

namespace App\Filters\Auth;

use Lightpack\Http\Request;
use Lightpack\Http\Response;
use Lightpack\Filters\IFilter;

class WebFilter implements IFilter
{
    public function before(Request $request)
    {
        if(auth()->isGuest()) {
            auth()->redirectLoginUrl();
        }
    }

    public function after(Request $request, Response $response): Response
    {
        return $response;
    }
}