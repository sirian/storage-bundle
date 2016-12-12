<?php

namespace Sirian\StorageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ThumbFiltersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('sirian_storage.thumb_manager');

        $taggedServices = $container->findTaggedServiceIds('sirian_storage.filter');

        foreach ($taggedServices as $id => $tags) {
            $registry->addMethodCall('addFilter', [new Reference($id)]);
        }
    }
}
