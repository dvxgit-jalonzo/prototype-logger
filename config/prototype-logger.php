<?php

return [
    'prototype_log_path' => env('PROTOTYPE_LOG_PATH', storage_path("logs")),
    'prototype_log_folder' => env('PROTOTYPE_LOG_FOLDER', 'prototype-logger'),
    'prototype_log_prefix' => env('PROTOTYPE_LOG_PREFIX', 'logger'),
    'prototype_log_extension' => env('PROTOTYPE_LOG_EXTENSION', 'log'),
];
