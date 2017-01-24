<?php


namespace AdminPanel\Symfony\AdminBundle;

use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\DataGridPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\DataSourcePass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TemplatePathPass;
use Doctrine\Common\Annotations\AnnotationReader;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\AdminElementPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ContextPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\SetEventDispatcherPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use AdminPanel\Symfony\AdminBundle\DependencyInjection\FSIAdminExtension;
use AdminPanel\Symfony\AdminBundle\Finder\AdminClassFinder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminPanelBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AdminAnnotatedElementPass(
            new AnnotationReader(),
            new AdminClassFinder()
        ));
        $container->addCompilerPass(new AdminElementPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new KnpMenuBuilderPass());
        $container->addCompilerPass(new ResourceRepositoryPass());
        $container->addCompilerPass(new ManagerVisitorPass());
        $container->addCompilerPass(new ContextPass());
        $container->addCompilerPass(new TwigGlobalsPass());
        $container->addCompilerPass(new SetEventDispatcherPass());
        $container->addCompilerPass(new DataGridPass());
        $container->addCompilerPass(new DataSourcePass());
        $container->addCompilerPass(new TemplatePathPass());
    }

    /**
     * @return \AdminPanel\Symfony\AdminBundle\DependencyInjection\FSIAdminExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminExtension();
        }

        return $this->extension;
    }
}
