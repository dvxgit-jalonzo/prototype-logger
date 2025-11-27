<?php

namespace NormanAlonzo\PrototypeLogger;

class PrototypeLogger
{
    protected string $logDirectory;
    protected string $logFile;

    public function __construct(string $logDirectory, string $filename = 'app.log')
    {
        $this->logDirectory = rtrim($logDirectory, '/');
        $this->logFile = $this->logDirectory . '/' . $filename;

        $this->ensureDirectoryExists();
    }

    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->logDirectory)) {
            mkdir($this->logDirectory, 0777, true);
        }
    }

    public function write(string $message, string $level = 'INFO'): void
    {
        $date = date('Y-m-d H:i:s');
        $formatted = "[$date] [$level] $message" . PHP_EOL;

        file_put_contents($this->logFile, $formatted, FILE_APPEND | LOCK_EX);
    }
}
