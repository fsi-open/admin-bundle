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

class TwigGlobalsPassSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Definition $def
     */
    function let($container, $def)
    {
        $container->hasDefinition('twig')->willReturn(true);
        $container->findDefinition('twig')->willReturn($def);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Definition $def
     */
    function it_adds_globals($container, $def)
    {
        $container->getParameter(Argument::any())->willReturn('test');
        $def->addMethodCall('addGlobal', Argument::containing('test'))->shouldBeCalled();

        $this->process($container);
    }
} 
