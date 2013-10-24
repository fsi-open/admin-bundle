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

class FSiAdminBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\FSiAdminBundle');
    }

    function it_is_bundle()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_add_compiler_pass(ContainerBuilder $builder)
    {
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextBuilderPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminElementPass'))
            ->shouldBeCalled();

        $this->build($builder);
    }
}
