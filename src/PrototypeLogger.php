<?php

namespace Nrmnalonzo\PrototypeLogger;

class PrototypeLogger
{
    protected string $logDir;
    protected string $logPrefix;
    protected string $logExtension;

    protected static string $staticLogDir;
    protected static string $staticLogPrefix;
    protected static string $staticLogExtension;
    protected static bool $staticConfigured = false;

    public function __construct(?string $logDir = null, ?string $logPrefix = null, ?string $logExtension = null)
    {
        // Use config values if not provided
        $config = self::loadConfig();

        $this->logDir = $logDir ?? $config['log_dir'];
        $this->logPrefix = $logPrefix ?? $config['log_prefix'];
        $this->logExtension = $logExtension ?? $config['log_extension'];

        $this->ensureLogDirectoryExists();
    }

    public static function configure(?string $logDir = null, ?string $logPrefix = null, ?string $logExtension = null): void
    {
        $config = self::loadConfig();

        self::$staticLogDir = $logDir ?? $config['log_dir'];
        self::$staticLogPrefix = $logPrefix ?? $config['log_prefix'];
        self::$staticLogExtension = $logExtension ?? $config['log_extension'];

        self::ensureStaticLogDirectoryExists();
        self::$staticConfigured = true;
    }

    protected function ensureLogDirectoryExists(): void
    {
        $subFolder = date('Ym');
        $fullPath = rtrim($this->logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $subFolder;

        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
            }
        }

        $this->logDir = $fullPath;
    }

    protected static function ensureStaticLogDirectoryExists(): void
    {
        $subFolder = date('Ym');
        $fullPath = rtrim(self::$staticLogDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $subFolder;

        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
            }
        }

        self::$staticLogDir = $fullPath;
    }

    public function log(string $message, ?array $context = null): void
    {
        self::writeLog($message, $context, $this->logDir, $this->logPrefix, $this->logExtension);
    }

    public static function trail(string $message, ?array $context = null): void
    {
        if (!self::$staticConfigured) {
            self::configure(); // Load default config
        }

        self::writeLog($message, $context, self::$staticLogDir, self::$staticLogPrefix, self::$staticLogExtension);
    }

    protected static function writeLog(string $message, ?array $context, string $logDir, string $logPrefix, string $logExtension): void
    {
        $datePrefix = date('Ymd');
        $fileName = $datePrefix . '_' . $logPrefix . '.' . $logExtension;
        $filePath = $logDir . DIRECTORY_SEPARATOR . $fileName;

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = $context ? ' ' . json_encode([$context], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';

        $logEntry = "[$timestamp] $message$contextStr" . PHP_EOL;

        file_put_contents($filePath, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Load config from prototype-logger.php
     */
    protected static function loadConfig(): array
    {
        $configPath = __DIR__ . '/../config/prototype-logger.php';

        if (!file_exists($configPath)) {
            throw new \RuntimeException("Config file not found: $configPath");
        }

        return include $configPath;
    }
}
