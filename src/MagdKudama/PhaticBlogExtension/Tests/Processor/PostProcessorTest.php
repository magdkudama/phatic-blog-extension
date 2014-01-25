<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Collection\PermalinkCollection;
use MagdKudama\PhaticBlogExtension\Parser\PostsParser;
use MagdKudama\PhaticBlogExtension\Permalink\DatePermalink;
use MagdKudama\PhaticBlogExtension\Permalink\PermalinkGuesser;
use MagdKudama\PhaticBlogExtension\Permalink\PrefixPermalink;
use MagdKudama\PhaticBlogExtension\Processor\PostProcessor;
use MagdKudama\PhaticBlogExtension\View\ViewExtension;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Loader_Filesystem;
use Twig_Environment;

class PostProcessorTest extends TestCase
{
    public function setUp()
    {
        $fileSystem = new Filesystem();
        foreach (['site1', 'site2', 'site3'] as $site) {
            $fileSystem->mkdir(__DIR__ . '/Fixtures/' . $site . '/result/');
        }
    }

    public function tearDown()
    {
        m::close();
        $fileSystem = new Filesystem();

        foreach (['site1', 'site2', 'site3'] as $site) {
            $fileSystem->remove(__DIR__ . '/Fixtures/' . $site . '/result/');
            $fileSystem->mkdir(__DIR__ . '/Fixtures/' . $site . '/result/');
        }
    }

    /**
     * @dataProvider siteProvider
     */
    public function testDumpMethod($siteName, $expectedResult)
    {
        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('get')
            ->with('phatic.finder')
            ->andReturn(new Finder());

        $container
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig([
                'posts_path' => __DIR__ . '/Fixtures/' . $siteName . '/_posts/'
            ]));


        $parser = new PostsParser($container);

        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('get')
            ->with('phatic.finder')
            ->andReturn(new Finder());
        $container
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig([
                'results_path' => __DIR__ . '/Fixtures/' . $siteName . '/result/'
            ]));
        $container
            ->shouldReceive('get')
            ->with('blog_posts_collection')
            ->andReturn($parser);

        $loader = new Twig_Loader_Filesystem([
            __DIR__ . '/Fixtures/' . $siteName . '/_pages/',
            __DIR__ . '/Fixtures/' . $siteName . '/_posts/'
        ]);
        $view = new Twig_Environment($loader);
        $viewContainer = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $baseMock = m::mock('MagdKudama\PhaticBlogExtension\Parser\BaseParser');
        $baseMock->shouldReceive('read')
            ->andReturnNull();

        $viewContainer
            ->shouldReceive('get')
            ->with('blog_posts_collection')
            ->andReturn($baseMock);
        $viewContainer
            ->shouldReceive('get')
            ->with('blog_pages_collection')
            ->andReturn($baseMock);
        $view->addExtension(new ViewExtension($viewContainer));

        $container
            ->shouldReceive('get')
            ->with('phatic.twig')
            ->andReturn($view);

        $container
            ->shouldReceive('get')
            ->with('phatic.filesystem')
            ->andReturn(new Filesystem());

        $dispatcher = m::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->shouldReceive('dispatch')
            ->andReturnNull();

        $container
            ->shouldReceive('get')
            ->with('phatic.dispatcher')
            ->andReturn($dispatcher);

        $permalinkCollection = new PermalinkCollection();
        $permalinkCollection->add(new DatePermalink());
        $permalinkCollection->add(new PrefixPermalink());
        $guesser = new PermalinkGuesser($permalinkCollection, [
            'type' => 'prefix',
            'param' => null
        ]);

        $container
            ->shouldReceive('get')
            ->with('blog_permalink_guesser')
            ->andReturn($guesser);

        $processor = new PostProcessor($container);
        foreach ($processor->getCollection() as $element) {
            $processor->dump($element);
        }

        $tempFinder = new Finder();
        $this->assertEquals(
            $expectedResult,
            $tempFinder->files()->in(__DIR__ . '/Fixtures/' . $siteName . '/result/')->count()
        );
    }

    public function siteProvider()
    {
        return [
            ['site1', 3],
            ['site2', 2],
            ['site3', 0],
        ];
    }

}