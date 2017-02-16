<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ContextPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container, Definition $def)
    {
        $container->hasDefinition('admin.context.manager')->willReturn(true);
        $container->findDefinition('admin.context.manager')->willReturn($def);
    }

    function it_add_context_builders_into_context_manager($container, $def, $fooDef)
    {
        $container->findTaggedServiceIds('admin.context')->willReturn(array(
            'builder_foo' => array(array()),
        ));

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $def->replaceArgument(0, array($fooDef))->shouldBeCalled();

        $this->process($container);
    }

    public function it_add_builders_in_priority_order(
        ContainerBuilder $container,
        Definition $def,
        Definition $fooDef,
        Definition $barDef,
        Definition $bazDef
    ) {
        $container->findTaggedServiceIds('admin.context')->willReturn(array(
            'builder_baz' => array(array('priority' => -10)),
            'builder_bar' => array(array()),
            'builder_foo' => array(array('priority' => 5)),
        ));

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $container->findDefinition('builder_bar')->willReturn($barDef);
        $container->findDefinition('builder_baz')->willReturn($bazDef);
        $def->replaceArgument(0, array($fooDef, $barDef, $bazDef))->shouldBeCalled();

        $this->process($container);
    }
}
