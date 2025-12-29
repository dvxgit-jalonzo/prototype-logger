<?php

namespace Nrmnalonzo\PrototypeLogger;

use Illuminate\Support\ServiceProvider;

class PrototypeLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/prototype-logger.php' => config_path('prototype-logger.php'),
        ], 'prototype-logger');

        $envPath = base_path('.env');

        $defaults = [
            'PROTOTYPE_LOG_PATH="' . str_replace('\\', '/', storage_path('logs')) . '"',
            'PROTOTYPE_LOG_FOLDER=prototype-logger',
            'PROTOTYPE_LOG_PREFIX=logger',
            'PROTOTYPE_LOG_EXTENSION=log',
        ];

        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            foreach ($defaults as $line) {
                $key = explode('=', $line)[0];

                if (!str_contains($envContent, $key . '=')) {
                    file_put_contents($envPath, PHP_EOL . $line, FILE_APPEND);
                }
            }
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/prototype-logger.php', 'prototype-logger');
    }
}
