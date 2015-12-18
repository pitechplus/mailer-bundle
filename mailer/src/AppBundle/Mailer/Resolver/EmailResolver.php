<?php

namespace AppBundle\Mailer\Resolver;

use AppBundle\Mailer\Provider\MailerProviderInterface;
use AppBundle\Mailer\Mailer;

class EmailResolver
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var MailerProviderInterface
     */
    private $configProvider;

    /**
     * @param   Mailer                  $mailer
     * @param   MailerProviderInterface $configProvider
     */
    public function __construct(
        Mailer $mailer,
        MailerProviderInterface $configProvider
    ) {
        $this->mailer = $mailer;
        $this->configProvider = $configProvider;
    }

    /**
     * Resolve email information for mailer from yaml helper
     *
     * @param string $type     Corresponds to new entry in mailers.yml
     * @param string $to       List of emails to send the mail to, if set to
     *                         null it will be taken from mailers.yml
     * @param string $params   Array of params to be send to subject in
     *                         translation message and view/template either
     *                         translation (if string) or template (if
     *                         configured in mailers.yml)
     * @param string $format   The format of the mail. Ex: 'text/html'.
     * @param string $filename Filepath to filename to attach, if null 
     *                         defaults to configration in mailers.yml. If no
     *                         configuration, no attachement is sent.
     */
    public function sendEmail(
        $type,
        $to = null,
        $params = [],
        $format = 'text/html',
        $filename = null
    ) {
        $this
            ->mailer
            ->sendEmail(
                $this
                    ->configProvider
                    ->getSubject(
                        $type,
                        isset($params['subject']) ? $params['subject'] : []
                    ),
                $this->configProvider->getFrom($type),
                $to ? $to : $this->configProvider->getTo($type),
                $this
                    ->configProvider
                    ->getBody(
                        $type,
                        isset($params['view']) ? $params['view'] : []
                    ),
                $format,
                (
                    $filename ?
                    $filename :
                    $this->configProvider->getAttachement($type)
                ),
                $filename ? basename($filename) : null
            );
    }
}
