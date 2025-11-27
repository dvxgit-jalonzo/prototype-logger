<?php

namespace Nrmnalonzo\PrototypeLogger;

use Illuminate\Support\ServiceProvider;

class PrototypeLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/prototype-logger.php' => config_path('prototype-logger.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/prototype-logger.php', 'prototype-logger');
    }
}
