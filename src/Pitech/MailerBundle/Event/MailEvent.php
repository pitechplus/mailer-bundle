<?php

namespace Pitech\MailerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MailEvent extends Event
{
    const MAIL_EVENT_NAME = 'pitech_mailer.mail_event';

    public function getType()
    {
        return null;
    }

    public function getTo()
    {
        return null;
    }

    public function getParams()
    {
        return [];
    }

    public function getName()
    {
        return self::MAIL_EVENT_NAME;
    }
}
