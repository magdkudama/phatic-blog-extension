<?php

namespace MagdKudama\PhaticBlogExtension;

use MagdKudama\Phatic\Extension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PhaticBlogExtension implements Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setParameter('phatic.blog.base_url', $config['base_url']);
        $container->setParameter('phatic.blog.post_prefix', $config['post_prefix']);

        $appConfig = $container->getParameter('phatic.app_config');
        $resultantPath = $appConfig['results_path'];
        if (null !== $config['post_prefix']) {
            $resultantPath .= $config['post_prefix'] . '/';
        }

        $container->setParameter('phatic.blog.results_posts_path', $resultantPath);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/Config')
        );
        $loader->load('services.yml');
    }

    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->
            children()
                ->scalarNode('base_url')->isRequired()->end()
                ->scalarNode('post_prefix')->isRequired()->end()
            ->end();
    }

    public function getExtensionDependency()
    {
        return null;
    }
}