<?php

namespace LoggerType;

use LoggerLevel;

abstract class AbstractLogger
{
    protected int $level;

    public function __construct(int $level) {
        $this->level = $level;
    }

    abstract public function logWarning(string $message);
    abstract public function logDebug(string $message);
    abstract public function logInfo(string $message);
    abstract public function logError(string $message);

    public function logMessage(string $message, int $level)
    {
        switch ($level) {
            case LoggerLevel::DEBUG :
                return $this->logDebug($message);
            case LoggerLevel::INFO :
                return $this->logInfo($message);
            case LoggerLevel::WARNING :
                return $this->logWarning($message);
            case LoggerLevel::ERROR:
                return $this->logError($message);
        }
    }
}