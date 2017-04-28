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

class FSiAdminBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_add_compiler_pass(ContainerBuilder $builder)
    {
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(
                Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminElementPass'),
                PassConfig::TYPE_REMOVE
            )->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\SetEventDispatcherPass'))
            ->shouldBeCalled();

        $this->build($builder);
    }
}
