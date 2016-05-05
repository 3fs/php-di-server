<?php

return function () {
    return [
        ['GET', '/', 'default'],
        ['GET', '/callback', function () {
            return 'callback response';
        }],
        ['GET', '/query', 'parameter'],
        ['POST', '/postData', 'parameter'],
        ['GET', '/headers', 'headers'],
        ['GET', '/html', 'html'],
        ['GET', '/redirect', 'redirect'],
    ];
};
