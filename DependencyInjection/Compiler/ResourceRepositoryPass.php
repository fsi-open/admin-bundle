<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasExtension('fsi_resource_repository')) {
            $container->removeDefinition(ResourceRepositoryContext::class);
        }
    }
}
