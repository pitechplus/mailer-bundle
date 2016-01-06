<?php

namespace Pitech\MailerBundle\Tests\Model;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Pitech\MailerBundle\Model\MailMessage;

class MailMessageTest extends BaseTest
{
    /** @var MailMessage */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->class = new MailMessage();
    }

    /**
     * @dataProvider getDataForTestGetAttachment
     *
     * @param null|string $attachment
     * @param null|string $attachmentName
     * @param null|string $attachmentPath
     */
    public function testGetAttachment($attachment, $attachmentName, $attachmentPath)
    {
        $this->class->setAttachment($attachment);

        $this->assertEquals($this->class->getAttachmentName(), $attachmentName);
        $this->assertEquals($this->class->getAttachmentPath(), $attachmentPath);
    }

    /**
     * @return array
     */
    public function getDataForTestGetAttachment()
    {
        return [
            [
                sprintf('%s/../Resources/public/file1.yml', __DIR__),
                'file1.yml',
                sprintf('%s/../Resources/public', __DIR__)
            ],
            [
                null,
                null,
                null
            ],
            [
                sprintf('%s/../Resources/public/file2.yml', __DIR__),
                'file2.yml',
                sprintf('%s/../Resources/public', __DIR__)
            ],
            [
                sprintf('%s/../Resources/public/file3.yml', __DIR__),
                'file3.yml',
                sprintf('%s/../Resources/public', __DIR__)
            ],
            [
                sprintf('%s/../Resources/public/file5.yml', __DIR__),
                'file5.yml',
                sprintf('%s/../Resources/public', __DIR__)
            ],
        ];
    }
}
