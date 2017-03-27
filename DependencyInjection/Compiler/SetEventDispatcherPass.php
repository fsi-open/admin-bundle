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
use Symfony\Component\DependencyInjection\Reference;

/**
 * @deprecated since version 2.1
 *
 * This compiler was created as a backwards-compatibile way of injecting the
 * EventDispatcher into the Controller\ControllerAbstract. In version 3.0 it is
 * replaced by directly injecting it to the controller's constructor and was so
 * removed.
 */
class SetEventDispatcherPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ids = array_keys($container->findTaggedServiceIds('admin.event_dispatcher_aware'));
        if (empty($ids)) {
            return;
        }

        $eventDispatcher = new Reference('event_dispatcher');
        foreach ($ids as $id) {
            $definition = $container->findDefinition($id);
            if (!$definition->hasMethodCall('setEventDispatcher')) {
                $definition->addMethodCall('setEventDispatcher', [$eventDispatcher]);
            }
        }
    }
}
