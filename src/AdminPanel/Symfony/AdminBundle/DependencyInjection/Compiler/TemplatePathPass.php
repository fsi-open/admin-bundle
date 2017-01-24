<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use AdminPanel\Symfony\AdminBundle\AdminPanelBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TemplatePathPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loaderDefinition = null;

        if ($container->hasDefinition('twig.loader.filesystem')) {
            $loaderDefinition = $container->getDefinition('twig.loader.filesystem');
        } elseif ($container->hasDefinition('twig.loader')) {
            // Symfony 2.0 and 2.1 were not using an alias for the filesystem loader
            $loaderDefinition = $container->getDefinition('twig.loader');
        }

        if (null === $loaderDefinition) {
            return;
        }

        $refl = new \ReflectionClass(AdminPanelBundle::class);
        $path = dirname($refl->getFileName()).'/Resources/views';
        $loaderDefinition->addMethodCall('addPath', [$path]);
    }
}
