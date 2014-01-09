<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Parser;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\HomepageParser;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Finder\Finder;

class HomepageParserTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        $this->builder = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->builder
            ->shouldReceive('get')
            ->with('phatic.finder')
            ->andReturn(new Finder());
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @dataProvider siteProvider
     */
    public function testAddedElementsToCollection($path, $count)
    {
        $this->builder
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig(['layouts_path' => $path]));

        $parser = new HomepageParser($this->builder);
        $collection = $parser->read();

        $this->assertEquals(
            $count,
            count($collection)
        );
    }

    /**
     * @expectedException MagdKudama\PhaticBlogExtension\Exception\PageNotFoundException
     */
    public function testPageNotFoundThrowsException()
    {
        $this->builder
            ->shouldReceive('get')
            ->with('phatic.config')
            ->andReturn(new ApplicationConfig(['layouts_path' => __DIR__ . '/Fixtures/site3/_pages/_layouts/']));

        $parser = new HomepageParser($this->builder);
        $parser->read();
    }

    public function siteProvider()
    {
        return [
            [__DIR__ . '/Fixtures/site1/_pages/_layouts/', 1],
            [__DIR__ . '/Fixtures/site2/_pages/_layouts/', 1]
        ];
    }
}