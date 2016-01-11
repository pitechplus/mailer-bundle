<?php

namespace Pitech\MailerBundle\Mailer;

use Psr\Log\LoggerInterface;
use Pitech\MailerBundle\Model\MailMessageInterface;

class SwiftMailer implements MailerInterface
{
    const INFO_LOG_MESSAGE = '%s sent an e-mail to %s with subject "%s".';
    const CONTEXT_LOG = 'SwiftMailer';

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \Swift_Mailer   $mailer
     * @param LoggerInterface $logger
     */
    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param MailMessageInterface $message
     */
    public function sendMail(MailMessageInterface $message)
    {
        $email = \Swift_Message::newInstance()
            ->setSubject($message->getSubject())
            ->setTo($message->getTo())
            ->setFrom($message->getFrom())
            ->setBody($message->getBody(), 'text/html');

        if ($message->getCc()) {
            $email = $email->setCc($message->getCc());
        }

        if ($message->getAttachmentPath() && $message->getAttachmentName()) {
            $email = $email->attach(
                new \Swift_Attachment(
                    file_get_contents($message->getAttachmentPath()),
                    $message->getAttachmentName()
                )
            );
        }

        try {
            $this->mailer->send($email);

            $this->logger->info(
                sprintf(self::INFO_LOG_MESSAGE, $message->getFrom(), $message->getTo(), $message->getSubject()),
                [self::CONTEXT_LOG]
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [self::CONTEXT_LOG]);
        }
    }
}
