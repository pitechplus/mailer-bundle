<?php

namespace Pitech\MailerBundle\Tests\Templating;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Prophecy\Prophecy\ObjectProphecy;
use Pitech\MailerBundle\Templating\TwigTemplating;

class TwigTemplatingTest extends BaseTest
{
    /** @var ObjectProphecy */
    protected $twigEngine;

    /** @var TwigTemplating */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->twigEngine = $this->prophesize('Symfony\Bundle\TwigBundle\TwigEngine');
        $this->class = new TwigTemplating($this->twigEngine->reveal());
    }

    /**
     * @dataProvider getDataForTestRender
     *
     * @param string|null $template
     * @param array       $params
     */
    public function testRender($template, $params)
    {
        $this
            ->twigEngine
            ->render($template, $params)
            ->shouldBeCalledTimes(1);

        $this->class->render($template, $params);
    }

    /**
     * @return array
     */
    public function getDataForTestRender()
    {
        return [
            ['template1', []],
            ['template2', ['param1' => 'val1', 'param2' => 'val2']],
            [null, []],
            [null, ['param1' => 'val1']],
            ['template5', ['param1' => 'val1']]
        ];
    }
}
