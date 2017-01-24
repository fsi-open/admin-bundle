<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

class ResourceFSiCKEditorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = new Definition('FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\FSiCKEditorType');
        $definition->addTag('resource.type', ['alias' => 'fsi_ckeditor']);

        $container->setDefinition('fsi_resource_repository.resource.type.fsi_ckeditor', $definition);
    }
}
