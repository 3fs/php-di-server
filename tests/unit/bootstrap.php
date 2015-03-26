<?php

// set timezone
date_default_timezone_set('UTC');

// ensure augmented types are on
ini_set('augmented_types.enforce_by_default', 1);

// list of excluded files/directories for augmented types
if (function_exists('augmented_types_blacklist')) {
    augmented_types_blacklist([
        __DIR__ . '/../../vendor',
    ]);
}
