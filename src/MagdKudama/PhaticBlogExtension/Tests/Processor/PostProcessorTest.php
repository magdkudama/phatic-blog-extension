<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\PostsParser;
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
        $finder = new Finder();
        $fileSystem = new Filesystem();

        $loader = new Twig_Loader_Filesystem([
            __DIR__ . '/Fixtures/' . $siteName . '/_pages/',
            __DIR__ . '/Fixtures/' . $siteName . '/_posts/'
        ]);

        $view = new Twig_Environment($loader);
        $viewContainer = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $viewContainer
            ->shouldReceive('get')
            ->with('blog_posts_collection')
            ->andReturn(null);
        $viewContainer
            ->shouldReceive('get')
            ->with('blog_pages_collection')
            ->andReturn(null);

        $view->addExtension(new ViewExtension($viewContainer));
        $dispatcher = m::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->shouldReceive('dispatch')
            ->andReturnNull();
        $appConfig = new ApplicationConfig([
            'results_path' => __DIR__ . '/Fixtures/' . $siteName . '/result/'
        ]);

        $processor = new PostProcessor($finder, $fileSystem, $view, $dispatcher, $appConfig);

        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('get')
            ->with('phatic.finder')
            ->andReturn(new Finder());

        $container
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig([
                'layouts_path' => __DIR__ . '/Fixtures/' . $siteName . '/_pages/_layouts/',
                'posts_path' => __DIR__ . '/Fixtures/' . $siteName . '/_posts/'
            ]));


        $parser = new PostsParser($container);
        $processor->setCollection($parser);

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