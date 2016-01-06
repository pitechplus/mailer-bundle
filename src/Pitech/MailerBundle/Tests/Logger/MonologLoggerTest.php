<?php

namespace Pitech\MailerBundle\Tests\Logger;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Prophecy\Prophecy\ObjectProphecy;
use Pitech\MailerBundle\Logger\MonologLogger;

class MonologLoggerTest extends BaseTest
{
    /** @var ObjectProphecy */
    protected $logger;

    /** @var MonologLogger */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->logger = $this->prophesize('Monolog\Logger');
        $this->class = new MonologLogger($this->logger->reveal());
    }

    /**
     * @dataProvider getDataForTestLogger
     *
     * @param null|string $message
     * @param array       $context
     */
    public function testLogInfo($message, $context)
    {
        $this
            ->logger
            ->addInfo($message, $context)
            ->shouldBeCalledTimes(1);

        $this->class->logInfo($message, $context);
    }

    /**
     * @dataProvider getDataForTestLogger
     *
     * @param null|string $message
     * @param array       $context
     */
    public function testLogError($message, $context)
    {
        $this
            ->logger
            ->addError($message, $context)
            ->shouldBeCalledTimes(1);

        $this->class->logError($message, $context);
    }

    /**
     * @return array
     */
    public function getDataForTestLogger()
    {
        return [
            ['log1', []],
            ['log2', ['context2']],
            ['log3', ['context3']],
            [null, []],
            [null, ['context5']]
        ];
    }
}
