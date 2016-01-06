<?php

namespace Pitech\MailerBundle\Tests\EventListener;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Prophecy\Prophecy\ObjectProphecy;
use Pitech\MailerBundle\EventListener\EventEmailSubscriber;

class EventEmailSubscriberTest extends BaseTest
{
    /** @var ObjectProphecy */
    protected $mailResolver;

    /** @var ObjectProphecy */
    protected $event;

    /** @var EventEmailSubscriber */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->mailResolver = $this->prophesize('Pitech\MailerBundle\Resolver\MailResolver');
        $this->event = $this->prophesize('Pitech\MailerBundle\Event\MailEvent');
        $this->class = new EventEmailSubscriber($this->mailResolver->reveal());
    }

    public function testSendMail()
    {
        $this
            ->event
            ->getType()
            ->shouldBeCalledTimes(1)
            ->willReturn('email_type1');

        $this
            ->event
            ->getTo()
            ->shouldBeCalledTimes(1)
            ->willReturn('to@email.com');

        $this
            ->event
            ->getParams()
            ->shouldBeCalledTimes(1)
            ->willReturn([]);

        $this
            ->mailResolver
            ->sendMail('email_type1', 'to@email.com', [])
            ->shouldBeCalledTimes(1);

        $this->class->sendMail($this->event->reveal());
    }
}
