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

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ContextBuilderPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.context.manager')) {
            return;
        }

        $builders = array();
        foreach ($container->findTaggedServiceIds('admin.context.builder') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $builders[$priority][] = $container->findDefinition($id);
        }

        if (empty($builders)) {
            return;
        }

        krsort($builders);

        $builders = call_user_func_array('array_merge', $builders);

        $container->findDefinition('admin.context.manager')->replaceArgument(0, $builders);
    }
}
