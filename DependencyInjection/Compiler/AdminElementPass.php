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
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminElementPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.manager')) {
            return;
        }

        $elementServices = $container->findTaggedServiceIds('admin.element');
        foreach ($elementServices as $id => $tag) {
            $element = $container->findDefinition($id);

            $this->handleDataGridAwareElement($element, $container);
            $this->handleDataSourceAwareElement($element, $container);
            $this->handleFormAwareElement($element, $container);
            $this->handleDoctrineAwareElement($element, $container);

            $group = (isset($tag[0]['alias'])) ? $tag[0]['alias'] : null;

            $container->findDefinition('admin.manager')->addMethodCall('addElement', array(
                $element,
                $group,
            ));
        }
    }

    /**
     * @param Definition $definition
     * @param ContainerBuilder $container
     */
    private function handleDataGridAwareElement(Definition $definition, ContainerBuilder $container)
    {
        $implements = class_implements($definition->getClass());

        if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface', $implements)) {
            $definition->addMethodCall('setDataGridFactory', array($container->findDefinition('datagrid.factory')));
        }
    }

    /**
     * @param Definition $definition
     * @param ContainerBuilder $container
     */
    private function handleDataSourceAwareElement(Definition $definition, ContainerBuilder $container)
    {
        $implements = class_implements($definition->getClass());

        if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface', $implements)) {
            $definition->addMethodCall('setDataSourceFactory', array($container->findDefinition('datasource.factory')));
        }
    }


    /**
     * @param Definition $definition
     * @param ContainerBuilder $container
     */
    private function handleFormAwareElement(Definition $definition, ContainerBuilder $container)
    {
        $implements = class_implements($definition->getClass());

        if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface', $implements)) {
            $definition->addMethodCall('setFormFactory', array($container->findDefinition('form.factory')));
        }
    }

    /**
     * @param Definition $definition
     * @param ContainerBuilder $container
     */
    private function handleDoctrineAwareElement(Definition $definition, ContainerBuilder $container)
    {
        $implements = class_implements($definition->getClass());

        if (in_array('FSi\Bundle\AdminBundle\Admin\Doctrine\DoctrineAwareInterface', $implements)
            || in_array('FSi\Bundle\AdminBundle\Doctrine\Admin\DoctrineAwareInterface', $implements)) {
            $definition->addMethodCall('setManagerRegistry', array($container->findDefinition('doctrine')));
        }
    }
}
