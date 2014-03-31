<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TwigGlobalsPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container, Definition $def)
    {
        $container->hasDefinition('twig')->willReturn(true);
        $container->findDefinition('twig')->willReturn($def);
    }

    function it_adds_globals(ContainerBuilder $container, Definition $def)
    {
        $container->getParameter(Argument::any())->willReturn('test');
        $def->addMethodCall('addGlobal', Argument::containing('test'))->shouldBeCalled();

        $this->process($container);
    }
} 
