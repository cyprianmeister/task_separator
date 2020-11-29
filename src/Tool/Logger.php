<?php

namespace App\Tool;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger
{
    public const INFO = MonologLogger::INFO;
    public const ERROR = MonologLogger::ERROR;

    private static $instance;

    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new MonologLogger('app');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../app.log'));
    }

    public static function use(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log(string $message, array $context = [], int $level = Logger::INFO): void
    {
        $this->logger->log($level, $message, $context);
    }
}
