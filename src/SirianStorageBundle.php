<?php

namespace Sirian\StorageBundle;

use Sirian\StorageBundle\DependencyInjection\Compiler\ThumbFiltersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SirianStorageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ThumbFiltersCompilerPass());
    }
}
