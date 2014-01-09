<?php

namespace MagdKudama\PhaticBlogExtension\Tests;

use MagdKudama\PhaticBlogExtension\Utils;

class UtilsTest extends TestCase
{
    /**
     * @dataProvider wordProvider
     */
    public function testStripSlash($word, $expected)
    {
        $this->assertEquals(
            $expected,
            Utils::stripSlash($word)
        );
    }

    public function wordProvider()
    {
        return [
            ['lorem/', 'lorem/'],
            ['lorem', 'lorem'],
            ['/lorem', 'lorem'],
        ];
    }
}