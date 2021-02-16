<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ContextPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition('admin.context.manager')) {
            return;
        }

        $contexts = [];
        foreach ($container->findTaggedServiceIds('admin.context') as $id => $tags) {
            $priority = $tags[0]['priority'] ?? 0;
            $contexts[$priority][] = $container->findDefinition($id);
        }

        if (0 === count($contexts)) {
            return;
        }

        krsort($contexts);
        $contexts = array_merge(...$contexts);

        $container->findDefinition('admin.context.manager')->replaceArgument(0, $contexts);
    }
}
