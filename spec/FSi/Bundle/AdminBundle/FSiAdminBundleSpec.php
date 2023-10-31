<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle;

use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\FlashMessagesPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminBundleSpec extends ObjectBehavior
{
    public function it_is_a_bundle(): void
    {
        $this->shouldBeAnInstanceOf(Bundle::class);
    }

    public function it_adds_compiler_passes(ContainerBuilder $builder): void
    {
        $builder
            ->addCompilerPass(Argument::type(FlashMessagesPass::class))
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->addCompilerPass(Argument::type(ResourceRepositoryPass::class))
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->addCompilerPass(Argument::type(TwigGlobalsPass::class))
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $builder->hasExtension('fsi_translatable')->willReturn(false);

        $this->build($builder);
    }
}
