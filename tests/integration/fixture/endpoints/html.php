<?php

use \trifs\DIServer\Http;

return function ($app) {
    return [
        'data'        => 'test',
        'contentType' => Http::CONTENT_TYPE_HTML,
    ];
};
