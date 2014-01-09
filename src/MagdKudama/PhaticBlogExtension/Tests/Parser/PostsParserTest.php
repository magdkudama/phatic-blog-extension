<?php

namespace MagdKudama\PhaticBlogExtension\Tests\Parser;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\PhaticBlogExtension\Parser\PostsParser;
use Mockery as m;
use MagdKudama\PhaticBlogExtension\Tests\TestCase;
use Symfony\Component\Finder\Finder;

class PostsParserTest extends TestCase
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
            ->andReturn(new ApplicationConfig(['posts_path' => $path]));

        $parser = new PostsParser($this->builder);
        $collection = $parser->read();

        $this->assertEquals(
            $count,
            count($collection)
        );
    }

    public function siteProvider()
    {
        return [
            [__DIR__ . '/Fixtures/site1/_posts/', 3],
            [__DIR__ . '/Fixtures/site2/_posts/', 2],
            [__DIR__ . '/Fixtures/site3/_posts/', 0],
        ];
    }
}