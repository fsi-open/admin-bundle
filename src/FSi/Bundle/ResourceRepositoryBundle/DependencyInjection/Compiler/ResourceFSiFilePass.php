<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourceFSiFilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = new Definition('FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\FSiFileType');
        $definition->addTag('resource.type', array('alias' => 'fsi_file'));
        $container->setDefinition('fsi_resource_repository.resource.type.fsi_file', $definition);

        $definition = new Definition('FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\FSiImageType');
        $definition->addTag('resource.type', array('alias' => 'fsi_image'));
        $container->setDefinition('fsi_resource_repository.resource.type.fsi_image', $definition);

        $definition = new Definition('FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\FSiRemovableFileType');
        $definition->addTag('resource.type', array('alias' => 'fsi_removable_file'));
        $container->setDefinition('fsi_resource_repository.resource.type.fsi_removable_file', $definition);
    }
}
