<?php

 /**
 * Register app filters.
 */

$routeFilters = $container->get('router')->filters();
$filtersConfig = $container->get('config')->get('filters');

foreach($routeFilters as $filterAlias) {
    if(!isset($filtersConfig[$filterAlias])) {
        throw new \Lightpack\Exceptions\FilterNotFoundException(
            "No filter class registered for: {$filterAlias}"
        );
    }

    $filter->register(
        $container->get('router')->route(), 
        new $filtersConfig[$filterAlias]
    );
}