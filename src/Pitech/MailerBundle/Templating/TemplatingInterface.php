<?php

namespace Pitech\MailerBundle\Templating;

interface TemplatingInterface
{
    public function render($template, array $params = []);
}
