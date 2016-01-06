<?php

namespace Pitech\MailerBundle\Resolver;

use Pitech\MailerBundle\Mailer\MailerInterface;
use Pitech\MailerBundle\Model\MailMessage;
use Pitech\MailerBundle\Provider\MailerProviderInterface;

class MailResolver
{
    const SUBJECT_INDEX = 'subject';
    const FROM_INDEX = 'from';
    const TO_INDEX = 'to';
    const BODY_INDEX = 'body';

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var MailerProviderInterface
     */
    protected $provider;

    /**
     * @param array                   $defaults
     * @param MailerInterface         $mailer
     * @param MailerProviderInterface $provider
     */
    public function __construct($defaults, MailerInterface $mailer, MailerProviderInterface $provider)
    {
        $this->defaults = $defaults;
        $this->mailer = $mailer;
        $this->provider = $provider;
    }

    /**
     * @param string|null $type
     * @param string|null $to
     * @param array       $params
     * @param string|null $filename
     */
    public function sendMail($type = null, $to = null, array $params = [], $filename = null)
    {
        if (!$to) {
            $to = $type && $this->provider->getTo($type)
                ? $this->provider->getTo($type)
                : $this->defaults[self::TO_INDEX];
        }

        $message = new MailMessage();
        $message->setSubject(
            $type && $this->provider->getSubject($type, isset($params['subject']) ? $params['subject'] : [])
                ? $this->provider->getSubject($type, isset($params['subject']) ? $params['subject'] : [])
                : $this->defaults[self::SUBJECT_INDEX]
        );
        $message->setFrom(
            $type && $this->provider->getFrom($type)
                ? $this->provider->getFrom($type)
                : $this->defaults[self::FROM_INDEX]
        );
        $message->setTo($to);
        $message->setBody(
            $type && $this->provider->getBody($type, isset($params['view']) ? $params['view'] : [])
                ? $this->provider->getBody($type, isset($params['view']) ? $params['view'] : [])
                : $this->defaults[self::BODY_INDEX]
        );
        $message->setCc($this->provider->getCc($type));
        $message->setAttachment($filename ? $filename : $this->provider->getAttachment($type));

        $this->mailer->sendMail($message);
    }
}
