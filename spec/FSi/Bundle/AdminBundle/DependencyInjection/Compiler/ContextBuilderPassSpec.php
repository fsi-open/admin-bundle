<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ContextBuilderPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextBuilderPass');
    }

    function it_add_context_builders_into_context_manager(ContainerBuilder $container, Definition $def, Definition $fooDef)
    {
        $container->hasDefinition('admin.context.manager')->shouldBeCalled()->willReturn(true);
        $container->findDefinition('admin.context.manager')->willReturn($def);

        $container->findTaggedServiceIds('admin.context.builder')->willReturn(array(
            'builder_foo' => array(array()),
        ));

        $container->findDefinition('builder_foo')->shouldBeCalled()->willReturn($fooDef);

        $def->replaceArgument(0, array($fooDef))->shouldBeCalled();
        $this->process($container);
    }
}
