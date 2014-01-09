<?php

namespace MagdKudama\PhaticBlogExtension\Tests;

use Mockery as m;
use MagdKudama\Phatic\Extension;
use MagdKudama\PhaticBlogExtension\PhaticBlogExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PhaticBlogExtensionTest extends TestCase
{
    /** @var Extension */
    protected $extension;

    protected $builder;

    public function setUp()
    {
        $this->extension = new PhaticBlogExtension();
        $this->builder = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder[getParameter,setParameter]');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testLoadingExtensionCreatesParametersInContainer()
    {
        $config = [
            'base_url' => 'test',
            'post_prefix' => 'prefix'
        ];

        $this->builder
            ->shouldReceive('getParameter')
            ->with('phatic.app_config')
            ->andReturn(['results_path' => __DIR__ . '/'])
            ->once();

        $this->builder
            ->shouldReceive('setParameter')
            ->with('phatic.blog.base_url', 'test')
            ->once();

        $this->builder
            ->shouldReceive('setParameter')
            ->with('phatic.blog.post_prefix', 'prefix')
            ->once();

        $this->builder
            ->shouldReceive('setParameter')
            ->with('phatic.blog.results_posts_path', __DIR__ . '/prefix/')
            ->once();

        $this->extension->load($config, $this->builder);
    }
}