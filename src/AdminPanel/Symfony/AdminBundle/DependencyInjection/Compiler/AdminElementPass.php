<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminElementPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.manager') || !$container->has('admin.manager.visitor.element_collection')) {
            return;
        }

        $elements = array();
        $elementServices = $container->findTaggedServiceIds('admin.element');
        foreach ($elementServices as $id => $tag) {
            $elements[] = new Reference($id);
        }

        $container->findDefinition('admin.manager.visitor.element_collection')->replaceArgument(0, $elements);
    }
}
