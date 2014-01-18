<?php

namespace MagdKudama\PhaticBlogExtension\DependencyInjection\Compiler;

use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class PermalinkPass implements CompilerPassInterface
{
    const CLASS_NAME = 'MagdKudama\PhaticBlogExtension\Permalink\PermalinkExtension';

    public function process(ContainerBuilder $container)
    {
        $permalinksDefinition = $container->getDefinition('phatic_blog.permalinks');

        $calls = [];
        foreach ($container->findTaggedServiceIds('phatic_blog.permalink') as $id => $attributes) {
            $refClass = new ReflectionClass($container->getDefinition($id)->getClass());
            if (!$refClass->implementsInterface(self::CLASS_NAME)) {
                throw new InvalidArgumentException("Permalink classes must implement interface " . self::CLASS_NAME);
            }

            $calls[] = ['add', [new Reference($id)]];
        }

        $permalinksDefinition->setMethodCalls($calls);
    }
}