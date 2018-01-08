<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminElementPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldBeAnInstanceOf(Bundle::class);
    }

    function it_add_compiler_pass(ContainerBuilder $builder)
    {
        $builder->addCompilerPass(Argument::type(AdminAnnotatedElementPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(AdminElementPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(KnpMenuBuilderPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(ResourceRepositoryPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(ManagerVisitorPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(ContextPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(TwigGlobalsPass::class))
            ->shouldBeCalled();

        $this->build($builder);
    }
}
