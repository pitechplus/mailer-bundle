<?php

namespace AppBundle\Event;

use Pitech\MailerBundle\Event\MailEvent;

class DefaultEmailEvent extends MailEvent
{
    public function getType()
    {
        return 'default_email';
    }

    public function getName()
    {
        return 'default_mail_event';
    }

    public function getParams()
    {
        return ['view' => ['name' => 'Adrian']];
    }
}
