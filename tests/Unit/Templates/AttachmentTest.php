<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates;

use FondBot\Tests\TestCase;
use FondBot\Templates\Attachment;

class AttachmentTest extends TestCase
{
    /**
     * @dataProvider types()
     *
     * @param string $type
     */
    public function test(string $type)
    {
        $attachment = new Attachment($type, $url = $this->faker()->url, $metadata = ['foo' => 'bar']);

        $array = ['type' => $type, 'path' => $url];

        $this->assertSame($type, $attachment->getType());
        $this->assertSame($url, $attachment->getPath());
        $this->assertSame($metadata, $attachment->getMetadata());
        $this->assertSame($array, $attachment->toArray());
    }

    public function test_possibleTypes()
    {
        $expected = ['file', 'image', 'audio', 'video'];
        $this->assertSame($expected, Attachment::possibleTypes());
    }

    public function types()
    {
        return [
            ['file'],
            ['image'],
            ['audio'],
            ['video'],
        ];
    }
}