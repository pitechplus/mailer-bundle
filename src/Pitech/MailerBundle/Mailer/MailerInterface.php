<?php

namespace Pitech\MailerBundle\Mailer;

use Pitech\MailerBundle\Model\MailMessageInterface;

interface MailerInterface
{
    public function sendMail(MailMessageInterface $message);
}
