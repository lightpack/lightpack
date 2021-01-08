<?php

use \Lightpack\Http\Request;
use \Lightpack\Http\Response;
use \Lightpack\Filters\IFilter;

class MockFilter implements IFilter
{
    public function before(Request $request)
    {
        if($request->isPost()) {
            $_POST['framework'] = 'Lightpack';
        }

        if($request->isGet()) {
            return new Response();
        }
    }

    public function after(Request $request, Response $response): Response
    {
        $response->setBody('hello');
        return $response;
    }
}