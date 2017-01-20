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
class ResourceFSiCKEditorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = new Definition('FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\FSiCKEditorType');
        $definition->addTag('resource.type', array('alias' => 'fsi_ckeditor'));

        $container->setDefinition('fsi_resource_repository.resource.type.fsi_ckeditor', $definition);
    }
}