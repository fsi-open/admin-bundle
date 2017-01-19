<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ContextPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.context.manager')) {
            return;
        }

        $contexts = array();
        foreach ($container->findTaggedServiceIds('admin.context') as $id => $tags) {
            $priority = isset($tags[0]['priority']) ? $tags[0]['priority'] : 0;
            $contexts[$priority][] = $container->findDefinition($id);
        }

        if (empty($contexts)) {
            return;
        }
        krsort($contexts);
        $contexts = call_user_func_array('array_merge', $contexts);

        $container->findDefinition('admin.context.manager')->replaceArgument(0, $contexts);
    }
}
