<?php

namespace MagdKudama\PhaticBlogExtension;

use MagdKudama\Phatic\Extension;
use MagdKudama\PhaticBlogExtension\DependencyInjection\Compiler\PermalinkPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PhaticBlogExtension implements Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setParameter('phatic.blog.base_url', $config['base_url']);

        $param = $config['permalink']['param'];
        if ($param != null) {
            if (substr($param, 0, 1) === '/') {
                $param .= substr($param, 1);
            }
            if (substr($param, -1) !== '/') {
                $param .= '/';
            }
        }

        $permalinkOptions = [
            'type' => $config['permalink']['type'],
            'param' => $param
        ];

        $container->setParameter('phatic.blog.permalink_options', $permalinkOptions);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/Config')
        );
        $loader->load('services.yml');

        $container->addCompilerPass(new PermalinkPass());
    }

    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->
            children()
                ->scalarNode('base_url')->isRequired()->end()
                    ->arrayNode('permalink')
                        ->isRequired()
                            ->children()
                                ->scalarNode('type')->isRequired()->end()
                                ->scalarNode('param')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function getExtensionDependency()
    {
        return null;
    }
}