<?php

namespace Pitech\MailerBundle\Provider;

interface MailerProviderInterface
{
    public function getBody($config, array $params = []);
    public function getSubject($config, array $params = []);
    public function getTo($config);
    public function getCc($config);
    public function getFrom($config);
    public function getAttachment($config);
}
