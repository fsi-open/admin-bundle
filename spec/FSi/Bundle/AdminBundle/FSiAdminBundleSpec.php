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
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminBundleSpec extends ObjectBehavior
{
    public function it_is_bundle(): void
    {
        $this->shouldBeAnInstanceOf(Bundle::class);
    }

    public function it_add_compiler_pass(ContainerBuilder $builder): void
    {
        $builder->addCompilerPass(Argument::type(ResourceRepositoryPass::class))->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(TwigGlobalsPass::class))->shouldBeCalled();

        $this->build($builder);
    }
}
