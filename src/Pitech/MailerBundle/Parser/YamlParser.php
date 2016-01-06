<?php

namespace Pitech\MailerBundle\Parser;

use Symfony\Component\Yaml\Parser;

class YamlParser implements FileParserInterface
{
    /**
     * @param string $fileName
     *
     * @return string|null
     */
    public function parse($fileName)
    {
        $parser = new Parser();

        return $parser->parse(file_get_contents(realpath($fileName)));
    }
}
