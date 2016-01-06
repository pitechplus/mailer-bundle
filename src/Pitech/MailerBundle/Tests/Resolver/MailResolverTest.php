<?php

namespace Pitech\MailerBundle\Tests\Resolver;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Prophecy\Prophecy\ObjectProphecy;
use Pitech\MailerBundle\Resolver\MailResolver;

class MailResolverTest extends BaseTest
{
    /** @var array */
    private static $defaults = [
        'to' => 'to@email.com',
        'from' => 'from@email.com',
        'subject' => 'default subject',
        'body' => 'default body'
    ];

    /** @var ObjectProphecy */
    protected $mailer;

    /** @var ObjectProphecy */
    protected $provider;

    /** @var ObjectProphecy */
    protected $message;

    /** @var MailResolver */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->mailer = $this->prophesize('Pitech\MailerBundle\Mailer\MailerInterface');
        $this->provider = $this->prophesize('Pitech\MailerBundle\Provider\MailerProviderInterface');
        $this->message = $this->prophesize('Pitech\MailerBundle\Model\MailMessageInterface');
        $this->class = new MailResolver(self::$defaults, $this->mailer->reveal(), $this->provider->reveal());
    }

    /**
     * @dataProvider getDataForTestSendMail
     *
     * @param null|string $type
     * @param null|string $to
     * @param array       $params
     * @param null|string $filename
     */
    public function testSendMail($type, $to, $params, $filename)
    {
        if (!$to && $type) {
            $this
                ->provider
                ->getTo($type)
                ->shouldBeCalled();
        }

        if ($type) {
            $this
                ->provider
                ->getSubject($type, $params)
                ->shouldBeCalled();

            $this
                ->provider
                ->getFrom($type)
                ->shouldBeCalled();

            $this
                ->provider
                ->getBody($type, $params)
                ->shouldBeCalled();
        }

        $this
            ->provider
            ->getCc($type)
            ->shouldBeCalled();

        if (!$filename) {
            $this
                ->provider
                ->getAttachment($type)
                ->shouldBeCalled();
        }

        $this->class->sendMail($type, $to, $params, $filename);
    }

    public function getDataForTestSendMail()
    {
        return [
            ['type', 'to@to.com', [], 'filename'],
            [null, 'to@to.com', [], null],
            ['type', null, [], null],
            ['type', null, [], null],
            ['type', 'to@to.com', [], null]
        ];
    }
}
