<?php

namespace spec\AdminPanel\Symfony\AdminBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class AdminPanelBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    function it_add_compiler_pass($builder)
    {
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(
            Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminElementPass'),
            PassConfig::TYPE_BEFORE_REMOVING
        )->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ContextPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\SetEventDispatcherPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\DataGridPass'))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type('AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TemplatePathPass'))
            ->shouldBeCalled();

        $this->build($builder);
    }
}
