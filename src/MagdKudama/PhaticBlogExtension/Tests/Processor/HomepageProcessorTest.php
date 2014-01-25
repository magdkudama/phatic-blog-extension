<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\HomepageParser;
use MagdKudama\PhaticBlogExtension\Processor\HomepageProcessor;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Loader_Filesystem;
use Twig_Environment;

class HomepageProcessorTest extends TestCase
{
    /** @var HomepageProcessor */
    protected $processor;

    /** @var HomepageParser */
    protected $parser;

    protected $container;

    public function setUp()
    {
        $this->container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('get')
            ->with('phatic.finder')
            ->andReturn(new Finder());
        $container
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig([
                'layouts_path' => __DIR__ . '/Fixtures/site1/_pages/_layouts/'
            ]));

        $this->parser = new HomepageParser($container);
        $this->container
            ->shouldReceive('get')
            ->with('blog_homepage_collection')
            ->andReturn($this->parser);
    }

    public function tearDown()
    {
        m::close();
        $fileSystem = new Filesystem();
        $fileSystem->remove(__DIR__ . '/Fixtures/site1/result/');
        $fileSystem->mkdir(__DIR__ . '/Fixtures/site1/result/');
    }

    public function testGetCollectionWorks()
    {
        $this->processor = new HomepageProcessor($this->container);

        $this->assertEquals(
            $this->parser->read(),
            $this->processor->getCollection()
        );
    }

    public function testDumpMethod()
    {
        $loader = new Twig_Loader_Filesystem([
            __DIR__ . '/Fixtures/site1/_pages/',
            __DIR__ . '/Fixtures/site1/_posts/'
        ]);
        $view = new Twig_Environment($loader);

        $this->container
            ->shouldReceive('get')
            ->with('phatic.twig')
            ->andReturn($view);

        $this->container
            ->shouldReceive('get')
            ->with('phatic.filesystem')
            ->andReturn(new Filesystem());

        $this->container
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig([
                'layouts_path' => __DIR__ . '/Fixtures/site1/_pages/_layouts/',
                'results_path' => __DIR__ . '/Fixtures/site1/result/'
            ]));

        $dispatcher = m::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->shouldReceive('dispatch')
            ->andReturnNull();

        $this->container
            ->shouldReceive('get')
            ->with('phatic.dispatcher')
            ->andReturn($dispatcher);

        $this->processor = new HomepageProcessor($this->container);
        foreach ($this->processor->getCollection() as $element) {
            $this->processor->dump($element);

            $this->assertFileExists(__DIR__ . '/Fixtures/site1/result/index.html');
        }
    }
}