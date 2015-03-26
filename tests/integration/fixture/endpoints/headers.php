<?php

use \trifs\DIServer\Http;

return function ($di) {
    return [
        'code' => Http::CODE_OK,
        'data' => [
            'test' => $di->request->getHeader('X-Test'),
        ],
    ];
};
