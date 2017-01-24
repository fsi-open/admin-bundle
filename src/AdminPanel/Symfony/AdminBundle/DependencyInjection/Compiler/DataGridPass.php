<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataGridPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('datagrid.extension')) {
            return;
        }

        $columns = [];

        foreach ($container->findTaggedServiceIds('datagrid.column') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $columns[$alias] = $serviceId;
        }

        $container->getDefinition('datagrid.extension')->replaceArgument(1, $columns);

        $columnExtensions = [];

        foreach ($container->findTaggedServiceIds('datagrid.column_extension') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $columnExtensions[$alias] = $serviceId;
        }

        $container->getDefinition('datagrid.extension')->replaceArgument(2, $columnExtensions);

        $subscribers = [];

        foreach ($container->findTaggedServiceIds('datagrid.subscriber') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $subscribers[$alias] = $serviceId;
        }

        $container->getDefinition('datagrid.extension')->replaceArgument(3, $subscribers);
    }
}
