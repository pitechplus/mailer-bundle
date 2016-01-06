<?php

namespace Pitech\MailerBundle\Logger;

use Monolog\Logger;

class MonologLogger implements LoggerInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logInfo($message, array $context = [])
    {
        $this->logger->addInfo($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logError($message, array $context = [])
    {
        $this->logger->addError($message, $context);
    }
}
