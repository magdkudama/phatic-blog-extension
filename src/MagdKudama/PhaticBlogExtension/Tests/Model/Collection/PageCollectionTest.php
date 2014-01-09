<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Model\Collection;

use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\PhaticBlogExtension\Model\Collection\PageCollection;
use MagdKudama\PhaticBlogExtension\Model\Page;

class PageCollectionTest extends TestCase
{
    /** @var PageCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new PageCollection();
    }

    public function testAddingAnElementWorks()
    {
        $this->collection->add(new Page());

        $this->assertEquals(
            1,
            count($this->collection),
            'Add method works as expected'
        );
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testClassIsTypeHinted()
    {
        $this->collection->add("fake");
    }

    public function testAddingElementChecksIfItsContained()
    {
        $page = new Page();
        $this->collection->add($page);
        $this->collection->add($page);

        $this->assertEquals(
            1,
            count($this->collection),
            'Only non-added elements are added'
        );
    }
}