## Configuration

### Default Configuration

The package uses the following default values from the config file:
```php
return [
    'log_path' => storage_path('logs'),     // Base directory for logs
    'log_folder' => 'prototype-logger',     // Subfolder within log_path
    'log_prefix' => 'logger',               // Prefix for log filenames
    'log_extension' => 'log',               // File extension
];
```

### Publishing Configuration

You can publish the configuration file to customize these values:
```bash
php artisan vendor:publish --tag=prototype-logger
```

This will create `config/prototype-logger.php` in your Laravel application.

## Usage

All parameters are optional. If not provided, the logger will use values from the configuration file.

### Static Usage (Recommended for Quick Logging)
```php
use Nrmnalonzo\PrototypeLogger\PrototypeLogger;

// Configure once (optional - uses config defaults if not called)
PrototypeLogger::configure(
    logPath: storage_path('logs'),
    logFolder: 'app_logs',
    logPrefix: 'application',
    logExtension: 'log'
);

// Log messages
PrototypeLogger::trail('User logged in', ['user_id' => 123]);
PrototypeLogger::trail('Payment processed', ['amount' => 99.99, 'currency' => 'USD']);
```

### Instance Usage (For Multiple Log Destinations)
```php
use Nrmnalonzo\PrototypeLogger\PrototypeLogger;

// Create a logger instance for employee logs
$employeeLogger = new PrototypeLogger(
    logPath: storage_path('logs'),
    logFolder: 'employee_logs',
    logPrefix: 'employee',
    logExtension: 'log'
);

// Create a logger instance for error logs
$errorLogger = new PrototypeLogger(
    logPath: storage_path('logs'),
    logFolder: 'errors',
    logPrefix: 'error',
    logExtension: 'log'
);

// Use different loggers for different purposes
$employeeLogger->log('Employee clocked in', ['employee_id' => 456]);
$errorLogger->log('Database connection failed', ['error' => 'Connection timeout']);
```

## Log File Structure

Logs are automatically organized by year and month:
```
storage/logs/
└── prototype-logger/           ← log_folder
    └── 202412/                 ← Year-Month subfolder (auto-created)
        ├── 20241201_logger.log
        ├── 20241202_logger.log
        └── 20241222_logger.log
```

Each log entry includes a timestamp:
```
[2024-12-22 14:30:45] User logged in [{"user_id":123}]
[2024-12-22 14:35:12] Payment processed [{"amount":99.99,"currency":"USD"}]
```