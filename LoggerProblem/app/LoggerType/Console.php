<?php

namespace LoggerType;

use LoggerLevel;

class Console extends AbstractLogger
{
    public function __construct(int $level) {
        parent::__construct($level);
    }

    public function logWarning(string $message)
    {
        if ($this->level >= LoggerLevel::WARNING) {
            echo 'Warning:' . self::class . $message;
        }
    }

    public function logDebug(string $message)
    {
        if ($this->level >= LoggerLevel::DEBUG) {
            echo 'Debug:' .self::class . $message;
        }
    }

    public function logInfo(string $message)
    {
        if ($this->level >= LoggerLevel::INFO) {
            echo 'Info:' . self::class . $message;
        }
    }

    public function logError(string $message)
    {
        if ($this->level >= LoggerLevel::ERROR) {
            echo 'Error:' . self::class . $message;
        }
    }
}