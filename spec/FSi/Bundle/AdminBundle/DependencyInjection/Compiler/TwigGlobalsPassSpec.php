<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TwigGlobalsPassSpec extends ObjectBehavior
{
    public function let(ContainerBuilder $container, Definition $definition): void
    {
        $container->hasDefinition('twig')->willReturn(true);
        $container->findDefinition('twig')->willReturn($definition);
        $definition
            ->addMethodCall(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($definition)
        ;
    }

    public function it_adds_globals(ContainerBuilder $container, Definition $definition): void
    {
        $container->getParameter(Argument::any())->willReturn('test');
        $definition
            ->addMethodCall('addGlobal', Argument::containing('test'))
            ->shouldBeCalled()
            ->willReturn($definition)
        ;

        $this->process($container);
    }
}
