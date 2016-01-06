<?php

namespace Pitech\MailerBundle\Tests\Parser;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Pitech\MailerBundle\Parser\YamlParser;

class YamlParserTest extends BaseTest
{
    const TEST_FILE_PATH = '/../Resources/public/';

    /** @var YamlParser */
    protected $class;

    /** @var string */
    protected $filePath;

    protected function setUp()
    {
        parent::setUp();

        $this->filePath = sprintf('%s%s', __DIR__, self::TEST_FILE_PATH);
        $this->class = new YamlParser();
    }

    /**
     * @dataProvider getDataForTestParse
     *
     * @param string $fileName
     * @param array  $fileContent
     */
    public function testParse($fileName, $fileContent)
    {
        $this->assertEquals(
            json_encode($fileContent),
            json_encode($this->class->parse(sprintf('%s%s', $this->filePath, $fileName)))
        );
    }

    /**
     * @return array
     */
    public function getDataForTestParse()
    {
        return [
            ['file1.yml', ['key1' => ['key2' => ['key3' => 'test']]]],
            ['file2.yml', ['key1' => ['key2' => ['key3' => ['key4' => ['key5' => 'test']]]]]],
            ['file3.yml', null]
        ];
    }
}
