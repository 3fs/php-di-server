<?php

// ensure augmented types are on
ini_set('augmented_types.enforce_by_default', 1);

// list of excluded files/directories for augmented types
if (function_exists('augmented_types_blacklist')) {
    augmented_types_blacklist([
        __DIR__ . '/../../../vendor',
    ]);
}

use \trifs\DIServer\ServiceProvider;
use \trifs\DI\Container;

require __DIR__ . '/../../../vendor/autoload.php';

$di = new Container;
$di->register(new ServiceProvider);
$di->config = function () {
    return [
        'endpoints' => __DIR__ . '/endpoints',
    ];
};
$di->routes = include __DIR__ . '/routes.php';

$di->server->run();
