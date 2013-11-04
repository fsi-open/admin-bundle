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
