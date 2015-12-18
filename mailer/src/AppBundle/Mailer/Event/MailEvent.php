<?php

namespace AppBundle\Mailer\Event;

use Symfony\Component\EventDispatcher\Event;

class MailEvent extends Event
{
    const MAIL = 'mailer_app.mail';

    public function getName()
    {
        return 'demo_mail';
    }
    
    public function getTo()
    {
        return '';
    }
    
    public function getViewParams()
    {
        return [];
    }
}
