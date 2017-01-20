<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataSourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('datasource.extension')) {
            return;
        }

        $driverFactories = array();

        foreach ($container->findTaggedServiceIds('datasource.driver.factory') as $serviceId => $tag) {
            $driverFactories[] = $container->getDefinition($serviceId);
        }

        $container->getDefinition('datasource.driver.factory.manager')
            ->replaceArgument(0, $driverFactories);

        $extensions = array();

        foreach ($container->findTaggedServiceIds('datasource.driver.extension') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
            ? $tag[0]['alias']
            : $serviceId;

            $extensions[$alias] = $serviceId;
        }

        $container->getDefinition('datasource.extension')->replaceArgument(1, $extensions);

        $subscribers = array();

        foreach ($container->findTaggedServiceIds('datasource.subscriber') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
            ? $tag[0]['alias']
            : $serviceId;

            $subscribers[$alias] = $serviceId;
        }

        $container->getDefinition('datasource.extension')->replaceArgument(2, $subscribers);

        foreach ($extensions as $driverExtension) {
            $driverType = $container->getDefinition($driverExtension)->getArgument(1);

            $fields = array();

            foreach ($container->findTaggedServiceIds('datasource.driver.'.$driverType.'.field') as $serviceId => $tag) {
                $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

                $fields[$alias] = $serviceId;
            }

            $container->getDefinition($driverExtension)->replaceArgument(2, $fields);

            $fieldSubscribers = array();

            foreach ($container->findTaggedServiceIds('datasource.driver.'.$driverType.'.field.subscriber') as $serviceId => $tag) {
                $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

                $fieldSubscribers[$alias] = $serviceId;
            }

            $container->getDefinition($driverExtension)->replaceArgument(3, $fieldSubscribers);

            $subscribers = array();

            foreach ($container->findTaggedServiceIds('datasource.driver.'.$driverType.'.subscriber') as $serviceId => $tag) {
                $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

                $subscribers[$alias] = $serviceId;
            }

            $container->getDefinition($driverExtension)->replaceArgument(4, $subscribers);
        }
    }
}
