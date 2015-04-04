<?php

use \trifs\DIServer\Http;

return function ($app) {
    return [
        'code' => Http::CODE_NOT_FOUND,
        'data' => '/ not valid',
    ];
};
