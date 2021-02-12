<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    public function it_does_nothing_when_there_is_no_resource_extension(ContainerBuilder $container): void
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(true);
        $container->removeDefinition(ResourceRepositoryContext::class)->shouldNotBeCalled();
        $this->process($container);
    }

    public function it_loads_resources_config_only_if_resource_repository_extension_exists(ContainerBuilder $container)
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(false);
        $container->removeDefinition(ResourceRepositoryContext::class)->shouldBeCalled();
        $this->process($container);
    }
}
