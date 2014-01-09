<?php

namespace MagdKudama\PhaticBlogExtension\Parser;

use MagdKudama\Phatic\Config\ApplicationConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

abstract class BaseParser
{
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /** @return ContainerBuilder */
    public function getContainer()
    {
        return $this->container;
    }

    /** @return Finder */
    public function getFinder()
    {
        return $this->getContainer()->get('phatic.finder')->create();
    }

    /** @return ApplicationConfig */
    public function getConfig()
    {
        return $this->getContainer()->get('phatic.config');
    }

    abstract function read();
}