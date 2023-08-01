<?php

use LoggerType\AbstractLogger;

class Logger
{
    private array $loggers = [];

    public function add(AbstractLogger $logger) {
        $this->loggers[] = $logger;
    }


    public function createLog(string $message, string $level) {
        foreach ($this->loggers as $logger) {
            $logger->logMessage($message, $level);
        }
    }

}