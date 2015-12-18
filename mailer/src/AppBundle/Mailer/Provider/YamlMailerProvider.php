<?php

namespace AppBundle\Mailer\Provider;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Parser;

class YamlMailerProvider implements MailerProviderInterface
{
    /**
    * @var Parser
    */
    private $parser;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var array
     */
    private $defaultParams;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param string              $filename
     * @param TwigEngine          $templating
     * @param TranslatorInterface $translator
     * @param array               $defaultParams
     */
    public function __construct(
        $filename,
        TwigEngine $templating,
        TranslatorInterface $translator,
        array $defaultParams
    ) {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->parser = new Parser();
        $this->defaultParams = $defaultParams;
        $this->data = $this
            ->parser
            ->parse(file_get_contents(realpath($filename)));
    }

    /**
     * Return translated subject or null
     *
     * @param string $mailerConfig
     * @param array  $params
     *
     * @return  string
     */
    public function getSubject($mailerConfig, $params = array())
    {
        if (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['subject'])
        ) {
            return $this->translator->trans(
                $this->data[$mailerConfig]['subject'],
                $params,
                'emails'
            );
        }

        return null;
    }

    /**
     * Returns a rendered html template, if the template option is set, otherwise,
     * if the body option is set, it translates the message it represents or returns
     * a string.
     *
     * @param string $mailerConfig
     * @param array  $params
     *
     * @return string
     */
    public function getBody($mailerConfig, $params = array())
    {
        if (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['template'])
        ) {
            return $this->templating->render(
                $this->data[$mailerConfig]['template'],
                $params
            );
        }

        if (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['body'])
        ) {
            return $this->translator->trans(
                $this->data[$mailerConfig]['body'],
                $params,
                'emails'
            );
        }

        return null;
    }

    /**
     * @param string $mailerConfig
     *
     * @return string|array To email.
     */
    public function getTo($mailerConfig)
    {
        if (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['to'])) {
            $to = $this->data[$mailerConfig]['to'];

            return preg_match('/^%(.*)%$/', $to)
                ? $this->paramContainer->get(str_replace('%', '', $to))
                : $to;
        }

        if (array_key_exists('to', $this->defaultParams)) {
            return $this->defaultParams['to'];
        }
    }

    /**
     * @param string $mailerConfig
     *
     * @return string From email. E.g: <noreply@pitechplus.com>
     */
    public function getFrom($mailerConfig)
    {
        return (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['from']))
                ? $this->data[$mailerConfig]['from']
                : (
                    array_key_exists('to', $this->defaultParams)
                    ? $this->defaultParams['to']
                    : ''
                );
    }

    /**
     * @param string $mailerConfig
     *
     * @return string Attachement filepath.
     */
    public function getAttachement($mailerConfig)
    {
        return (isset($this->data[$mailerConfig])
            && isset($this->data[$mailerConfig]['attachement']))
                ? $this->data[$mailerConfig]['attachement']
                : null;
    }
}
