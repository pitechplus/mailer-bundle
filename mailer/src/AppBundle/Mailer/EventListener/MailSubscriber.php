<?php

namespace AppBundle\Mailer\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Mailer\Resolver\EmailResolver;
use AppBundle\Mailer\Event\MailEvent;

/**
 * This is how you can dispatch a MailEvent from the controller:
 * $this->get('event_dispatcher')->dispatch(MailEvent::MAIL, new MailEvent());
 * Take advantage of MailEvent in order to provide the mail specific data.
 */
class MailSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailResolver
     */
    private $emailResolver;

    public static function getSubscribedEvents()
    {
        return [
            MailEvent::MAIL => 'sendEmail',
        ];
    }

    /**
     * @param EmailResolver $emailResolver
     */
    public function __construct(EmailResolver $emailResolver)
    {
        $this->emailResolver = $emailResolver;
    }

    /**
     * Send email on custom user events
     *
     * @param Event $event
     */
    public function sendEmail(Event $event)
    {
        $this->emailResolver->sendEmail(
            $event->getName(),
            $event->getTo(),
            ['view' => $event->getViewParams()]
        );
    }
}
