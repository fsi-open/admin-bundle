<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ManagerVisitorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.manager')) {
            return;
        }

        $elementServices = $container->findTaggedServiceIds('admin.manager.visitor');
        foreach (array_keys($elementServices) as $id) {
            $visitor = $container->findDefinition($id);

            $container->findDefinition('admin.manager')->addMethodCall('accept', [$visitor]);
        }
    }
}
