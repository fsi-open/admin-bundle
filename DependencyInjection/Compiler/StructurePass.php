<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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
class StructurePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.group.manager')) {
            return;
        }

        $groups = array();
        $groupIds = $container->getParameter('admin.groups');
        foreach ($groupIds as $groupId => $groupConfiguration) {
            $group = $container->findDefinition($groupId);

            $groupElements = array();
            foreach ($groupConfiguration['elements'] as $elementId) {
                $element = $container->findDefinition($elementId);
                $implements = class_implements($element->getClass());

                if (in_array('FSi\Bundle\AdminBundle\Structure\AdminElementInterface', $implements)) {
                    $element->addMethodCall('setFormFactory', array($container->findDefinition('form.factory')));
                    $element->addMethodCall('setDataSourceFactory', array($container->findDefinition('datasource.factory')));
                    $element->addMethodCall('setDataGridFactory', array($container->findDefinition('datagrid.factory')));
                }

                if (in_array('FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface', $implements)) {
                    $element->addMethodCall('setManagerRegistry', array($container->findDefinition('doctrine')));
                }

                $groupElements[$elementId] = $element;
            }

            $group->addMethodCall('setElements', array($groupElements));
            $group->addMethodCall('setId', array($groupId));

            $groups[$groupId] = $group;
        }

        $container->getDefinition('admin.group.manager')->replaceArgument(0, $groups);
    }
}