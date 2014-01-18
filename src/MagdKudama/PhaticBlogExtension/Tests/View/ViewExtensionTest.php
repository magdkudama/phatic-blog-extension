<?php

namespace MagdKudama\PhaticBlogExtension\Tests\View;

use MagdKudama\PhaticBlogExtension\Permalink\PermalinkGuesser;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Collection\PermalinkCollection;
use MagdKudama\PhaticBlogExtension\Model\Post;
use MagdKudama\PhaticBlogExtension\Permalink\DatePermalink;
use MagdKudama\PhaticBlogExtension\Permalink\PrefixPermalink;
use MagdKudama\PhaticBlogExtension\View\ViewExtension;
use MagdKudama\Phatic\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\SplFileInfo;

class ViewExtensionTest extends TestCase
{
    /** @var ContainerBuilder */
    protected $container;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testBaseUrlMethod()
    {
        $this->container->setParameter('phatic.blog.base_url', 'http://myurl.com');
        $extension = new ViewExtension($this->container);

        $this->assertEquals(
            'http://myurl.com',
            $extension->getBaseUrl()
        );
    }

    /**
     * @dataProvider baseUrlProvider
     */
    public function testGetUrlMethod($url)
    {
        $this->container->setParameter('phatic.blog.base_url', $url);
        $extension = new ViewExtension($this->container);

        $this->assertEquals(
            'http://myurl.com/test',
            $extension->getUrl('test')
        );
    }

    /**
     * @dataProvider urlForPostProvider
     */
    public function testGetUrlForPostMethod($prefix, $expected)
    {
        $permalinkCollection = new PermalinkCollection();
        $permalinkCollection->add(new DatePermalink());
        $permalinkCollection->add(new PrefixPermalink());

        $guesser = new PermalinkGuesser($permalinkCollection, [
            'type' => 'prefix',
            'param' => $prefix
        ]);

        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('get')
            ->with('blog_permalink_guesser')
            ->andReturn($guesser);

        $container
            ->shouldReceive('getParameter')
            ->with('phatic.blog.base_url')
            ->andReturn('http://myurl.com');

        $extension = new ViewExtension($container);

        $post = new Post();
        $post->setSlug('slug');

        $this->assertEquals(
            $expected,
            $extension->getUrlForPost($post)
        );
    }

    public function testGetPostContentRouteMethod()
    {
        $extension = new ViewExtension($this->container);

        $fakeFileInfo = new SplFileInfo('test', 'test', 'test');
        $post = new Post();
        $post->setFile($fakeFileInfo);

        $this->assertEquals(
            'test/content.html',
            $extension->getPostContentRoute($post)
        );
    }

    /**
     * @dataProvider assetProvider
     */
    public function testAssetMethod($asset, $expected)
    {
        $this->container->setParameter('phatic.blog.base_url', 'http://myurl.com');
        $extension = new ViewExtension($this->container);

        $this->assertEquals(
            $expected,
            $extension->asset($asset, false)
        );
    }

    /**
     * @dataProvider summarizeProvider
     */
    public function testSummarizeMethod($text, $quantity, $expected)
    {
        $extension = new ViewExtension($this->container);

        $this->assertEquals(
            $expected,
            $extension->summarize($text, $quantity)
        );
    }

    public function baseUrlProvider()
    {
        return [
            ['http://myurl.com'],
            ['http://myurl.com/'],
        ];
    }

    public function urlForPostProvider()
    {
        return [
            ['post/', 'http://myurl.com/post/slug'],
            [null, 'http://myurl.com/slug'],
        ];
    }

    public function assetProvider()
    {
        return [
            ['css/test.css', 'http://myurl.com/assets/css/test.css'],
            ['/css/test.css', 'http://myurl.com/assets/css/test.css'],
            ['test.css', 'http://myurl.com/assets/test.css'],
        ];
    }

    public function summarizeProvider()
    {
        return [
            ['lorem ipsum dolor sit amet', 10, 'lorem ipsu...'],
            ['lorem ipsum', 10, 'lorem ipsu...'],
            ['lorem ipsu', 10, 'lorem ipsu'],
            ['lorem', 10, 'lorem']
        ];
    }
}