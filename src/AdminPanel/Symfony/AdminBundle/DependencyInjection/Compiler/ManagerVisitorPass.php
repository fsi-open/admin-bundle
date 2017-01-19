<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ManagerVisitorPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.manager')) {
            return;
        }

        $elementServices = $container->findTaggedServiceIds('admin.manager.visitor');
        foreach ($elementServices as $id => $tag) {
            $visitor = $container->findDefinition($id);

            $container->findDefinition('admin.manager')->addMethodCall('accept', array($visitor));
        }
    }
}
