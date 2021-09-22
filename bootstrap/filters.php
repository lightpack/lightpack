<?php

use Lightpack\Exceptions\FilterNotFoundException;

/**
 * Register app filters.
 */

$filtersConfig = config('filters');
$routeFilters = $container->get('router')->filters();

foreach ($routeFilters as $filterAlias) {
    if (!array_key_exists($filterAlias, $filtersConfig)) {
        throw new FilterNotFoundException(
            "No filter class registered for: {$filterAlias}"
        );
    }

    $container->get('filter')->register(
        $container->get('router')->route(),
        new $filtersConfig[$filterAlias]
    );
}
