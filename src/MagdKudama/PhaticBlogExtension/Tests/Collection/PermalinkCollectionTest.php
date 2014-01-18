<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Collection;

use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\PhaticBlogExtension\Collection\PermalinkCollection;
use Mockery as m;

class PermalinkCollectionTest extends TestCase
{
    /** @var PermalinkCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new PermalinkCollection();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testAddingAnElementWorks()
    {
        $mock = m::mock('MagdKudama\PhaticBlogExtension\Permalink\PermalinkExtension');
        $this->collection->add($mock);

        $this->assertEquals(
            1,
            count($this->collection)
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
        $mock = m::mock('MagdKudama\PhaticBlogExtension\Permalink\PermalinkExtension');
        $this->collection->add($mock);
        $this->collection->add($mock);

        $this->assertEquals(
            1,
            count($this->collection)
        );
    }
}