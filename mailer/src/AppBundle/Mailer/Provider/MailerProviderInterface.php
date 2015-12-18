<?php

namespace AppBundle\Mailer\Provider;

interface MailerProviderInterface
{
    public function getBody($mailerConfig, $params = array());
    public function getSubject($mailerConfig, $params = array());
    public function getTo($mailerConfig);
    public function getFrom($mailerConfig);
    public function getAttachement($mailerConfig);
}
