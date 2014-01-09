<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Parser;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\PagesParser;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Finder\Finder;

class PagesParserTest extends TestCase
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
            ->andReturn(new ApplicationConfig(['pages_path' => $path]));

        $parser = new PagesParser($this->builder);
        $collection = $parser->read();

        $this->assertEquals(
            $count,
            count($collection)
        );
    }

    public function siteProvider()
    {
        return [
            [__DIR__ . '/Fixtures/site1/_pages/', 1],
            [__DIR__ . '/Fixtures/site2/_pages/', 3],
            [__DIR__ . '/Fixtures/site3/_pages/', 0],
        ];
    }
}