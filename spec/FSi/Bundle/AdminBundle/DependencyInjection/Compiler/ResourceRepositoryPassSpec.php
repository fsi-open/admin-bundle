<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    public function it_does_nothing_when_there_is_no_resource_extension(ContainerBuilder $container): void
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(true);
        $container->removeDefinition(Argument::any())->shouldNotBeCalled();
        $this->process($container);
    }

    public function it_removes_resource_repository_context_when_there_is_no_extension(ContainerBuilder $container): void
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(false);
        $container->removeDefinition(ResourceRepositoryContext::class)->shouldBeCalled();
        $container->removeDefinition(MapBuilder::class)->shouldBeCalled();
        $container->removeDefinition(ResourceFormBuilder::class)->shouldBeCalled();
        $this->process($container);
    }
}
