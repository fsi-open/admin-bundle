<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle;

use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminElementPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ContextPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\DataGridPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\DataSourcePass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\SetEventDispatcherPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TemplatePathPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class AdminPanelBundleSpec extends ObjectBehavior
{
    public function it_is_bundle()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    public function it_add_compiler_pass($builder)
    {
        $builder->addCompilerPass(Argument::type(AdminAnnotatedElementPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(
            Argument::type(AdminElementPass::class),
            PassConfig::TYPE_BEFORE_REMOVING
        )->shouldBeCalled();
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
        $builder->addCompilerPass(Argument::type(SetEventDispatcherPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(DataGridPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(TemplatePathPass::class))
            ->shouldBeCalled();
        $builder->addCompilerPass(Argument::type(DataSourcePass::class))
            ->shouldBeCalled();

        $this->build($builder);
    }
}
