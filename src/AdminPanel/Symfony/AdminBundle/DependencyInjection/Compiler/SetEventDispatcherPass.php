<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

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
                $definition->addMethodCall('setEventDispatcher', array($eventDispatcher));
            }
        }
    }
}
