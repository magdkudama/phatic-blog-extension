<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Model;

use DateTime;
use MagdKudama\PhaticBlogExtension\Model\Post;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Finder\SplFileInfo;

class PostTest extends TestCase
{
    /** @var Post */
    protected $post;

    public function setUp()
    {
        $this->post = new Post();
    }

    public function testClassMethods()
    {
        $fakeFileInfo = new SplFileInfo('fake', 'fake', 'fake');
        $date = new DateTime();

        $this->post->setTitle('title');
        $this->post->setKeywords('k1,k2,k3');
        $this->post->setMetaDescription('Meta description');
        $this->post->setSlug('slug');
        $this->post->setContent('Content');
        $this->post->setCreatedAt($date);
        $this->post->setPageContent('test');
        $this->post->setFile($fakeFileInfo);

        $this->assertEquals(
            'title',
            $this->post->getTitle(),
            'Check title is correct'
        );

        $this->assertEquals(
            'k1,k2,k3',
            $this->post->getKeywords(),
            'Check keywords are correct'
        );

        $this->assertEquals(
            'Meta description',
            $this->post->getMetaDescription(),
            'Check meta description is correct'
        );

        $this->assertEquals(
            'slug',
            $this->post->getSlug(),
            'Check slug is correct'
        );

        $this->assertEquals(
            'Content',
            $this->post->getContent(),
            'Check content is correct'
        );

        $this->assertSame(
            $date,
            $this->post->getCreatedAt(),
            'Check date instance is correct'
        );

        $this->assertEquals(
            'test',
            $this->post->getPageContent(),
            'Check page content is correct'
        );

        $this->assertSame(
            $fakeFileInfo,
            $this->post->getFile(),
            'Check file instance is correct'
        );

        $this->assertEquals(
            'slug',
            $this->post,
            'Check toString method works'
        );
    }
}