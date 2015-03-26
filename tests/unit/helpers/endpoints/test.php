<?php

use \trifs\DIServer\Http;

return function (\trifs\DI\Container $app) {
    return [
        'code' => Http::CODE_OK,
        'data' => 'test',
    ];
};
