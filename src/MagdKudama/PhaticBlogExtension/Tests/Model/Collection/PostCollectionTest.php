<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Model\Collection;

use DateTime;
use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\PhaticBlogExtension\Model\Collection\PostCollection;
use MagdKudama\PhaticBlogExtension\Model\Post;

class PostCollectionTest extends TestCase
{
    /** @var PostCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new PostCollection();
    }

    public function testAddingAnElementWorks()
    {
        $this->collection->add(new Post());

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
        $post = new Post();
        $this->collection->add($post);
        $this->collection->add($post);

        $this->assertEquals(
            1,
            count($this->collection),
            'Only non-added elements are added'
        );
    }

    public function testSortingByDateWorks()
    {
        $post1 = new Post();
        $date1 = DateTime::createFromFormat('Y-m-d', '2014-01-01');
        $post1->setCreatedAt($date1);

        $post2 = new Post();
        $date2 = DateTime::createFromFormat('Y-m-d', '2014-01-05');
        $post2->setCreatedAt($date2);

        $post3 = new Post();
        $date3 = DateTime::createFromFormat('Y-m-d', '2013-11-01');
        $post3->setCreatedAt($date3);

        $post4 = new Post();
        $date4 = DateTime::createFromFormat('Y-m-d', '2014-01-10');
        $post4->setCreatedAt($date4);

        foreach ([$post1, $post2, $post3, $post4] as $post) {
            $this->collection->add($post);
        }

        $this->collection->orderByDate();

        $postDates = [];
        /** @var $post Post */
        foreach ($this->collection as $post) {
            $postDates[] = $post->getCreatedAt();
        }

        $expectedResult = [$date4, $date2, $date1, $date3];

        for ($i = 0; $i < count($postDates); $i++) {
            $this->assertEquals(
                $expectedResult[$i]->format('Y-m-d'),
                $postDates[$i]->format('Y-m-d'),
                'Check the dates are correctly ordered in the post collection'
            );
        }
    }
}