<?php

namespace Pitech\MailerBundle\Templating;

use Symfony\Bundle\TwigBundle\TwigEngine;

class TwigTemplating implements TemplatingInterface
{
    /**
     * @var TwigEngine
     */
    protected $twigEngine;

    /**
     * @param TwigEngine $twigEngine
     */
    public function __construct(TwigEngine $twigEngine)
    {
        $this->twigEngine = $twigEngine;
    }

    /**
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    public function render($template, array $params = [])
    {
        return $this->twigEngine->render($template, $params);
    }
}
