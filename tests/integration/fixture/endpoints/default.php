<?php

use \trifs\DIServer\Http;

return function ($di) {
    return [
        'code' => Http::CODE_NOT_FOUND,
        'data' => '/ not valid',
    ];
};
