<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Model;

use MagdKudama\PhaticBlogExtension\Model\Page;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Finder\SplFileInfo;

class PageTest extends TestCase
{
    /** @var Page */
    protected $page;

    public function setUp()
    {
        $this->page = new Page();
    }

    public function testClassMethods()
    {
        $fakeFileInfo = new SplFileInfo('fake', 'fake', 'fake');

        $this->page->setPageContent('test');
        $this->page->setFile($fakeFileInfo);

        $this->assertEquals(
            'test',
            $this->page->getPageContent(),
            'Check page content is correct'
        );

        $this->assertSame(
            $fakeFileInfo,
            $this->page->getFile(),
            'Check file instance is correct'
        );

        $this->assertEquals(
            'fake',
            $this->page,
            'Check toString method works'
        );
    }
}