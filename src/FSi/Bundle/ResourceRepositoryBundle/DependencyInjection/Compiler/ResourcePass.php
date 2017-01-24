<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\DependencyInjection\Compiler;

use FSi\Bundle\ResourceRepositoryBundle\Exception\CompilerPassException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ResourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = [];
        foreach ($container->findTaggedServiceIds('resource.type') as $serviceId => $tag) {
            if (!isset($tag[0]['alias'])) {
                throw new CompilerPassException(sprintf('Service %s missing alias attribute', $serviceId));
            }

            $resourceService = $container->getDefinition($serviceId);
            $resources[$tag[0]['alias']] = $resourceService->getClass();
        }

        $container->setParameter('fsi_resource_repository.resource.types', $resources);
    }
}
