<?php


namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

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
