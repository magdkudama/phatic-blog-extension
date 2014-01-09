<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\HomepageParser;
use MagdKudama\PhaticBlogExtension\Processor\HomepageProcessor;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
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

    public function setUp()
    {
        $finder = new Finder();
        $fileSystem = new Filesystem();
        $fileSystem->mkdir(__DIR__ . '/Fixtures/site1/result/');
        $loader = new Twig_Loader_Filesystem([
            __DIR__ . '/Fixtures/site1/_pages/',
            __DIR__ . '/Fixtures/site1/_posts/'
        ]);
        $view = new Twig_Environment($loader);
        $dispatcher = m::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $appConfig = new ApplicationConfig([
            'results_path' => __DIR__ . '/Fixtures/site1/result/'
        ]);

        $this->processor = new HomepageProcessor($finder, $fileSystem, $view, $dispatcher, $appConfig);

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
        $this->processor->setCollection($this->parser);
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
        $this->assertEquals(
            $this->parser->read(),
            $this->processor->getCollection()
        );
    }

    public function testDumpMethod()
    {
        foreach ($this->processor->getCollection() as $element) {
            $this->processor->dump($element);

            $this->assertFileExists(__DIR__ . '/Fixtures/site1/result/index.html');
        }
    }
}