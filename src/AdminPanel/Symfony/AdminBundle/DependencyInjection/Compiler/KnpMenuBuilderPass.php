<?php

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class KnpMenuBuilderPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.menu.knp.decorator.chain')) {
            return;
        }

        $decoratorServices = $container->findTaggedServiceIds('admin.menu.knp_decorator');

        $decorators = array();
        foreach ($decoratorServices as $id => $tag) {
            $decorators[] = $container->findDefinition($id);
        }

        $container->findDefinition('admin.menu.knp.decorator.chain')->replaceArgument(0, $decorators);
    }
}
