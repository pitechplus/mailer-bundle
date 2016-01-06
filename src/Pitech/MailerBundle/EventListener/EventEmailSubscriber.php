<?php

namespace Pitech\MailerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pitech\MailerBundle\Resolver\MailResolver;
use Pitech\MailerBundle\Event\MailEvent;

class EventEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailResolver
     */
    protected $mailResolver;

    /**
     * @param MailResolver $mailResolver
     */
    public function __construct(MailResolver $mailResolver)
    {
        $this->mailResolver = $mailResolver;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MailEvent::MAIL_EVENT_NAME => 'sendMail'
        ];
    }

    /**
     * @param MailEvent $event
     */
    public function sendMail(MailEvent $event)
    {
        $this->mailResolver->sendMail(
            $event->getType(),
            $event->getTo(),
            $event->getParams()
        );
    }
}
