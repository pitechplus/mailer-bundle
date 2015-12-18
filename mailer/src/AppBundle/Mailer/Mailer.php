<?php

namespace AppBundle\Mailer;

use Monolog\Logger;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param \Swift_Mailer $mailer
     * @param Logger        $logger
     */
    public function __construct(\Swift_Mailer $mailer, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

     /**
     * Send email with attachement if needed
     *
     * @param   string      $subject
     * @param   string      $from
     * @param   string      $to
     * @param   string      $body
     * @param   string      $format
     * @param   string|null $filePath
     * @param   string      $fileName
     */
    public function sendEmail(
        $subject,
        $from,
        $to,
        $body,
        $format,
        $filePath = null,
        $fileName = 'attachement.txt'
    ) {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $format);

        if ($filePath) {
            $message = $message
                ->attach(
                    new \Swift_Attachment(
                        file_get_contents($filePath),
                        $fileName
                    )
                );
        }

        try {
            $this->mailer->send($message);
            $this
                ->logger
                ->addRecord(
                    100,
                    sprintf(
                        'Mail sent to %s from %s with subject %s',
                        $to,
                        $from,
                        $subject
                    ),
                    ['EmailService']
                );
        } catch (\Exception $e) {
            $this->logger->addRecord(100, $e->getMessage(), ['EmailService']);
        }
    }
}
