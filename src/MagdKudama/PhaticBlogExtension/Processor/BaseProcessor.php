<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\Config\ApplicationConfig;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class BaseProcessor
{
    /** @var ContainerBuilder */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /** @return Twig_Environment */
    public function getView()
    {
        return $this->container->get('phatic.twig');
    }

    /** @return Filesystem */
    public function getFileSystem()
    {
        return $this->container->get('phatic.filesystem');
    }

    /** @return EventDispatcherInterface */
    public function getDispatcher()
    {
        return $this->container->get('phatic.dispatcher');
    }

    /** @return ApplicationConfig */
    public function getConfig()
    {
        return $this->container->get('phatic.config');
    }
}