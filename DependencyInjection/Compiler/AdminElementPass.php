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
            $implements = class_implements($element->getClass());

            if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface', $implements)) {
                $element->addMethodCall('setDataGridFactory', array($container->findDefinition('datagrid.factory')));
            }
            if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface', $implements)) {
                $element->addMethodCall('setDataSourceFactory', array($container->findDefinition('datasource.factory')));
            }
            if (in_array('FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface', $implements)) {
                $element->addMethodCall('setFormFactory', array($container->findDefinition('form.factory')));
            }
            if (in_array('FSi\Bundle\AdminBundle\Admin\Doctrine\DoctrineAwareInterface', $implements)) {
                $element->addMethodCall('setManagerRegistry', array($container->findDefinition('doctrine')));
            }

            $group = (isset($tag[0]['alias'])) ? $tag[0]['alias'] : null;

            $container->findDefinition('admin.manager')->addMethodCall('addElement', array(
                $element,
                $group,
            ));
        }
    }
}
