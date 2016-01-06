<?php

namespace Pitech\MailerBundle\Provider;

use Symfony\Component\Translation\TranslatorInterface;
use Pitech\MailerBundle\Parser\FileParserInterface;
use Pitech\MailerBundle\Templating\TemplatingInterface;

class YamlMailerProvider implements MailerProviderInterface
{
    const SUBJECT_INDEX = 'subject';
    const BODY_INDEX = 'body';
    const TEMPLATE_INDEX = 'template';
    const TO_INDEX = 'to';
    const CC_INDEX = 'cc';
    const FROM_INDEX = 'from';
    const ATTACHMENT_INDEX = 'attachment';

    /**
     * @var null|string
     */
    protected $data;

    public $file;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $translationDomain;

    /**
     * @var TemplatingInterface
     */
    protected $templating;

    /**
     * @param TranslatorInterface $translator
     * @param string              $file
     * @param FileParserInterface $parser
     * @param TemplatingInterface $templating
     * @param string              $translationDomain
     */
    public function __construct(
        TranslatorInterface $translator,
        $file,
        FileParserInterface $parser,
        TemplatingInterface $templating,
        $translationDomain
    ) {
        $this->file = $file;
        $this->translator = $translator;
        $this->data = $parser->parse($file);
        $this->templating = $templating;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @param string $config
     * @param array  $params
     *
     * @return string|null
     */
    public function getBody($config, array $params = [])
    {
        if (isset($this->data[$config]) && isset($this->data[$config][self::TEMPLATE_INDEX])) {
            return $this->templating->render($this->data[$config][self::TEMPLATE_INDEX], $params);
        }

        return (isset($this->data[$config]) && isset($this->data[$config][self::BODY_INDEX]))
            ? $this->translator->trans($this->data[$config][self::BODY_INDEX], $params, $this->translationDomain)
            : null;
    }

    /**
     * @param string $config
     * @param array  $params
     *
     * @return string|null
     */
    public function getSubject($config, array $params = [])
    {
        return (isset($this->data[$config]) && isset($this->data[$config][self::SUBJECT_INDEX]))
            ? $this->translator->trans($this->data[$config][self::SUBJECT_INDEX], $params, $this->translationDomain)
            : null;
    }

    /**
     * @param string $config
     *
     * @return string|null
     */
    public function getTo($config)
    {
        return (isset($this->data[$config]) && isset($this->data[$config][self::TO_INDEX]))
            ? $this->data[$config][self::TO_INDEX]
            : null;
    }

    /**
     * @param string $config
     *
     * @return array|null
     */
    public function getCc($config)
    {
        return (isset($this->data[$config]) && isset($this->data[$config][self::CC_INDEX]))
            ? $this->data[$config][self::CC_INDEX]
            : null;
    }

    /**
     * @param string $config
     *
     * @return string|null
     */
    public function getFrom($config)
    {
        return (isset($this->data[$config]) && isset($this->data[$config][self::FROM_INDEX]))
            ? $this->data[$config][self::FROM_INDEX]
            : null;
    }

    /**
     * @param string $config
     *
     * @return array|null
     */
    public function getAttachment($config)
    {
        return (isset($this->data[$config]) && isset($this->data[$config][self::ATTACHMENT_INDEX]))
            ? $this->data[$config][self::ATTACHMENT_INDEX]
            : null;
    }
}
