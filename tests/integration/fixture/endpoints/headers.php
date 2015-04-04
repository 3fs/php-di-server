<?php

use \trifs\DIServer\Http;

return function ($app) {
    return [
        'code' => Http::CODE_OK,
        'data' => [
            'test' => $app->request->getHeader('X-Test'),
        ],
    ];
};
