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
        $this->builder = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder[setParameter]');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testLoadingExtensionCreatesParametersInContainer()
    {
        $config = [
            'base_url' => 'test',
            'permalink' => [
                'type' => 'type',
                'param' => 'param'
            ]
        ];

        $this->builder
            ->shouldReceive('setParameter')
            ->with('phatic.blog.base_url', 'test')
            ->once();

        $this->builder
            ->shouldReceive('setParameter')
            ->with('phatic.blog.permalink_options', ['type' => 'type', 'param' => 'param/'])
            ->once();

        $this->extension->load($config, $this->builder);
    }
}