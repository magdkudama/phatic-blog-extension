<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\PagesParser;
use MagdKudama\PhaticBlogExtension\Processor\PageProcessor;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Loader_Filesystem;
use Twig_Environment;

class PageProcessorTest extends TestCase
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
                'pages_path' => __DIR__ . '/Fixtures/' . $siteName . '/_pages/'
            ]));


        $parser = new PagesParser($container);

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
            ->with('blog_pages_collection')
            ->andReturn($parser);

        $loader = new Twig_Loader_Filesystem([
            __DIR__ . '/Fixtures/' . $siteName . '/_pages/',
            __DIR__ . '/Fixtures/' . $siteName . '/_posts/'
        ]);
        $view = new Twig_Environment($loader);

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

        $processor = new PageProcessor($container);
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
            ['site1', 1],
            ['site2', 3],
            ['site3', 0],
        ];
    }
}