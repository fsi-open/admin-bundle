<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\DependencyInjection\Compiler;

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

        $refl = new \ReflectionClass('FSi\Bundle\DataSourceBundle\DataSourceBundle');
        $path = dirname($refl->getFileName()).'/Resources/views';
        $loaderDefinition->addMethodCall('addPath', array($path));
    }
}