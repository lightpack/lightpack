<?php

namespace Lightpack;

use Lightpack\Http\Response;
use Lightpack\Filters\Filter;
use Lightpack\Routing\Dispatcher;
use Lightpack\Container\Container;

final class App
{
    public static function run(Container $container): void 
    {
        /**
         * Prepare variables. 
         */
        $request = $container->get('request');
        $response = $container->get('response');
        $filter = $container->get('filter');
        $dispatcher = new Dispatcher($request, $container->get('router'));
        $route = $container->get('router')->route();

        /**
         * Boot app filters.
         */
        require_once DIR_BOOTSTRAP . '/filters.php';

        /**
         * Process before filters.
         */
        $result = $filter->processBeforeFilters($route);
        
        if($result instanceof Response) {
            $result->send();
            return;
        }

        /**
         * Dispatch app request.
         */
        $result = $dispatcher->dispatch();

        if($result instanceof Response) {
            $response = $result;
        }

        /**
         * Process after filters.
         */
        $filter->setResponse($response);
        $response = $filter->processAfterFilters($route);

        /**
         * Finally send the response.
         */
        $response->send();
    }
}