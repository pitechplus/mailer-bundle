<?php

namespace Pitech\MailerBundle\Logger;

interface LoggerInterface
{
    public function logInfo($message, array $context = []);
    public function logError($message, array $context = []);
}
