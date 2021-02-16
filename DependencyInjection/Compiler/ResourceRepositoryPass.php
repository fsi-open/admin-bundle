<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ResourceRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (true === $container->hasExtension('fsi_resource_repository')) {
            $loader = new XmlFileLoader(
                $container,
                new FileLocator(__DIR__ . '/../../Resources/config')
            );
            $loader->load('context/resource.xml');
        }
    }
}
