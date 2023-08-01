<?php

use LoggerType\Console;
use LoggerType\Email;

require './app/bootstrap.php';

$logger = new Logger();
$console = new Console(LoggerLevel::WARNING);
$email = new Email(LoggerLevel::ERROR);
$logger->add($console);
$logger->add($email);

$logger->createLog('ERROR MESSAGE', LoggerLevel::ERROR);
$logger->createLog('WARNING MESSAGE', LoggerLevel::WARNING);