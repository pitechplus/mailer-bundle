<?php

namespace Pitech\MailerBundle\Tests\Mailer;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Prophecy\Prophecy\ObjectProphecy;
use Pitech\MailerBundle\Mailer\SwiftMailer;

class SwiftMailerTest extends BaseTest
{
    /** @var ObjectProphecy */
    protected $mailer;

    /** @var ObjectProphecy */
    protected $logger;

    /** @var ObjectProphecy */
    protected $message;

    /** @var SwiftMailer */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->mailer = $this->prophesize('\Swift_Mailer');
        $this->logger = $this->prophesize('Pitech\MailerBundle\Logger\LoggerInterface');
        $this->message = $this->prophesize('Pitech\MailerBundle\Model\MailMessageInterface');

        $this->class = new SwiftMailer($this->mailer->reveal(), $this->logger->reveal());
    }

    /**
     * @dataProvider getDataForTestSendMail
     *
     * @param null|string $to
     * @param null|string $from
     * @param null|string $subject
     * @param null|string $body
     * @param array       $cc
     * @param null|string $attachmentName
     * @param null|string $attachmentPath
     */
    public function testSendMail($to, $from, $subject, $body, $cc, $attachmentName, $attachmentPath)
    {
        $this
            ->message
            ->getTo()
            ->shouldBeCalledTimes(2)
            ->willReturn($to);

        $this
            ->message
            ->getFrom()
            ->shouldBeCalledTimes(2)
            ->willReturn($from);

        $this
            ->message
            ->getSubject()
            ->shouldBeCalledTimes(2)
            ->willReturn($subject);

        $this
            ->message
            ->getBody()
            ->shouldBeCalledTimes(1)
            ->willReturn($body);

        $getCcCalls = count($cc) > 0 ? 2 : 1;

        $this
            ->message
            ->getCc()
            ->shouldBeCalledTimes($getCcCalls)
            ->willReturn($cc);

        $getAttachmentPathCalls = $attachmentPath ? $attachmentName ? 2 : 1 : 1;

        $this
            ->message
            ->getAttachmentPath()
            ->shouldBeCalledTimes($getAttachmentPathCalls)
            ->willReturn($attachmentPath);

        $getAttachmentNameCalls = $attachmentPath ? $attachmentName ? 2 : 1 : 0;

        $this
            ->message
            ->getAttachmentName()
            ->shouldBeCalledTimes($getAttachmentNameCalls)
            ->willReturn($attachmentName);

        $this
            ->logger
            ->logInfo(sprintf(SwiftMailer::INFO_LOG_MESSAGE, $from, $to, $subject), ['SwiftMailer'])
            ->shouldBeCalledTimes(1);

        $this->class->sendMail($this->message->reveal());
    }

    /**
     * @return array
     */
    public function getDataForTestSendMail()
    {
        return [
            ['to@to.com', 'from@from.com', 'subject', 'body', ['to1@to1.com'], null, null],
            [null, 'from@from.com', 'subject', 'body', ['to1@to1.com'], null, null],
            ['to@to.com', null, 'subject', 'body', ['to1@to1.com'], null, null],
            ['to@to.com', 'from@from.com', null, 'body', ['to1@to1.com', 'to2@to2.com'], null, null],
            ['to@to.com', 'from@from.com', null, null, ['to1@to1.com'], null, null],
            ['to@to.com', 'from@from.com', 'subject', 'body', [], null, null],
            ['to@to.com', 'from@from.com', null, null, [], 'file1.yml', sprintf('%s/../Resources/public/', __DIR__)],
            ['to@to.com', 'from@from.com', null, null, [], null, sprintf('%s/../Resources/public/', __DIR__)],
            ['to@to.com', 'from@from.com', null, null, [], 'file1.yml', null],
            [null, null, null, null, [], null, null]
        ];
    }
}
