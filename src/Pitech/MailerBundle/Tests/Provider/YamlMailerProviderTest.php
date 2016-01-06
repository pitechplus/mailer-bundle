<?php

namespace Pitech\MailerBundle\Tests\Provider;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Pitech\MailerBundle\Provider\YamlMailerProvider;

class YamlMailerProviderTest extends BaseTest
{
    const TEST_MAIL_FILE_PATH = 'test_emails.yml';
    const TEST_TRANSLATION_DOMAIN = 'emails';

    protected $translator;

    protected $templating;

    protected $parser;

    /** @var YamlMailerProvider */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->parser = $this->prophesize('Pitech\MailerBundle\Parser\FileParserInterface');
        $this->translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        $this->templating = $this->prophesize('Pitech\MailerBundle\Templating\TemplatingInterface');
    }

    /**
     * @param array $fileContent
     */
    private function initClass($fileContent)
    {
        $this->parser
            ->parse(self::TEST_MAIL_FILE_PATH)
            ->willReturn($fileContent)
            ->shouldBeCalledTimes(1);

        $this->class = new YamlMailerProvider(
            $this->translator->reveal(),
            self::TEST_MAIL_FILE_PATH,
            $this->parser->reveal(),
            $this->templating->reveal(),
            self::TEST_TRANSLATION_DOMAIN
        );
    }

    /**
     * @dataProvider getDataForGetBodyWithTemplate
     *
     * @param array  $fileContent
     * @param string $body
     */
    public function testGetBodyWithTemplate($fileContent, $body)
    {
        $this->initClass($fileContent);

        if (isset($fileContent['test_email']) && isset($fileContent['test_email']['template'])) {
            $this->templating
                ->render($fileContent['test_email']['template'], [])
                ->willReturn($body)
                ->shouldBeCalledTimes(1);
        }

        $this->assertEquals($body, $this->class->getBody('test_email'));
    }

    /**
     * @return array
     */
    public function getDataForGetBodyWithTemplate()
    {
        return [
            [['test_email' => ['template' => 'template_1']], 'Template 1'],
            [['test_email' => ['to' => 'to@email.com']], null],
            [['test_email' => ['template' => 'template_3', 'to' => 'to@email.com']], 'Template 3'],
            [['test_email' => ['body' => 'body_1', 'template' => 'template_4', 'to' => 'to@email.com']], 'Template 4'],
            [[], null]
        ];
    }

    /**
     * @dataProvider getDataForGetBodyWithoutTemplate
     *
     * @param array  $fileContent
     * @param string $translation
     */
    public function testGetBodyWithoutTemplate($fileContent, $translation)
    {
        $this->initClass($fileContent);

        if (isset($fileContent['test_email']) && isset($fileContent['test_email']['body'])) {
            $this->translator
                ->trans($fileContent['test_email']['body'], [], self::TEST_TRANSLATION_DOMAIN)
                ->willReturn($translation)
                ->shouldBeCalledTimes(1);
        }

        $this->assertEquals($translation, $this->class->getBody('test_email'));
    }

    /**
     * @return array
     */
    public function getDataForGetBodyWithoutTemplate()
    {
        return [
            [['test_email' => ['body' => 'body_1']], 'Body 1'],
            [['test_email' => ['to' => 'to@email.com']], null],
            [['test_email' => ['body' => 'body_3', 'to' => 'to@email.com']], 'Body 3'],
            [['test_email' => ['body' => 'body_4', 'to' => 'to@email.com']], 'Body 4'],
            [[], null]
        ];
    }

    /**
     * @dataProvider getDataForGetSubject
     *
     * @param array  $fileContent
     * @param string $translation
     */
    public function testGetSubject($fileContent, $translation)
    {
        $this->initClass($fileContent);

        if (isset($fileContent['test_email']) && isset($fileContent['test_email']['subject'])) {
            $this->translator
                ->trans($fileContent['test_email']['subject'], [], self::TEST_TRANSLATION_DOMAIN)
                ->willReturn($translation)
                ->shouldBeCalledTimes(1);
        }

        $this->assertEquals($translation, $this->class->getSubject('test_email'));
    }

    /**
     * @return array
     */
    public function getDataForGetSubject()
    {
        return [
            [['test_email' => ['subject' => 'test_subject_1']], 'Test Subject 1'],
            [['test_email' => ['to' => 'to@email.com']], null],
            [['test_email' => ['subject' => 'test_subject_3', 'to' => 'to@email.com']], 'Test Subject 3'],
            [['test_email' => ['from' => 'from@email.com', 'to' => 'to@email.com']], null],
            [[], null]
        ];
    }

    /**
     * @dataProvider getDataForGetTo
     *
     * @param array  $fileContent
     * @param string $to
     */
    public function testGetTo($fileContent, $to)
    {
        $this->initClass($fileContent);

        $this->assertEquals($to, $this->class->getTo('test_email'));
    }

    /**
     * @return array
     */
    public function getDataForGetTo()
    {
        return [
            [['test_email' => ['subject' => 'test_subject_1']], null],
            [['test_email' => ['to' => 'to@email.com']], 'to@email.com'],
            [['test_email' => ['subject' => 'test_subject_3', 'to' => 'to@email.com']], 'to@email.com'],
            [['test_email' => ['from' => 'from@email.com', 'subject' => 'test_subject_4']], null],
            [[], null]
        ];
    }

    /**
     * @dataProvider getDataForGetFrom
     *
     * @param array  $fileContent
     * @param string $from
     */
    public function testGetFrom($fileContent, $from)
    {
        $this->initClass($fileContent);

        $this->assertEquals($from, $this->class->getFrom('test_email'));
    }

    /**
     * @return array
     */
    public function getDataForGetFrom()
    {
        return [
            [['test_email' => ['subject' => 'test_subject_1']], null],
            [['test_email' => ['from' => 'from@email.com']], 'from@email.com'],
            [['test_email' => ['subject' => 'test_subject_3', 'to' => 'to@email.com']], null],
            [['test_email' => ['from' => 'from@email.com', 'subject' => 'test_subject_4']], 'from@email.com'],
            [[], null]
        ];
    }

    /**
     * @dataProvider getDataForGetCc
     *
     * @param array $fileContent
     * @param int   $ccCount
     */
    public function testGetCc($fileContent, $ccCount)
    {
        $this->initClass($fileContent);

        $this->assertEquals($ccCount, count($this->class->getCc('test_email')));
    }

    /**
     * @return array
     */
    public function getDataForGetCc()
    {
        return [
            [['test_email' => ['subject' => 'test_subject_1']], 0],
            [['test_email' => ['from' => 'from@email.com', 'cc' => ['cc1', 'cc2', 'cc3', 'cc4', 'cc5']]], 5],
            [['test_email' => ['subject' => 'test_subject_3', 'to' => 'to@email.com', 'cc' => ['cc']]], 1],
            [['test_email' => ['cc' => ['cc@email.com'], 'subject' => 'test_subject_4']], 1],
            [[], 0]
        ];
    }

    /**
     * @dataProvider getDataForGetAttachment
     *
     * @param array $fileContent
     * @param int   $attachmentsCount
     */
    public function testGetAttachment($fileContent, $attachmentsCount)
    {
        $this->initClass($fileContent);

        $this->assertEquals($attachmentsCount, count($this->class->getAttachment('test_email')));
    }

    /**
     * @return array
     */
    public function getDataForGetAttachment()
    {
        return [
            [['test_email' => ['subject' => 'test_subject_1']], 0],
            [['test_email' => ['from' => 'from@email.com', 'attachment' => ['a1', 'a2', 'a3', 'a4', 'a5']]], 5],
            [['test_email' => ['subject' => 'test_subject_3', 'to' => 'to@email.com', 'cc' => ['cc']]], 0],
            [['test_email' => ['attachment' => ['a1'], 'subject' => 'test_subject_4']], 1],
            [[], 0]
        ];
    }
}
