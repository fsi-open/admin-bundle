<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;

class ContextPassSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Definition $def
     */
    public function let($container, $def)
    {
        $container->hasDefinition('admin.context.manager')->willReturn(true);
        $container->findDefinition('admin.context.manager')->willReturn($def);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Definition $def
     * @param \Symfony\Component\DependencyInjection\Definition $fooDef
     */
    public function it_add_context_builders_into_context_manager($container, $def, $fooDef)
    {
        $container->findTaggedServiceIds('admin.context')->willReturn([
            'builder_foo' => [[]],
        ]);

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $def->replaceArgument(0, [$fooDef])->shouldBeCalled();

        $this->process($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Definition $def
     * @param \Symfony\Component\DependencyInjection\Definition $fooDef
     * @param \Symfony\Component\DependencyInjection\Definition $barDef
     * @param \Symfony\Component\DependencyInjection\Definition $bazDef
     */
    public function it_add_builders_in_priority_order($container, $def, $fooDef, $barDef, $bazDef)
    {
        $container->findTaggedServiceIds('admin.context')->willReturn([
            'builder_baz' => [['priority' => -10]],
            'builder_bar' => [[]],
            'builder_foo' => [['priority' => 5]],
        ]);

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $container->findDefinition('builder_bar')->willReturn($barDef);
        $container->findDefinition('builder_baz')->willReturn($bazDef);
        $def->replaceArgument(0, [$fooDef, $barDef, $bazDef])->shouldBeCalled();

        $this->process($container);
    }
}
