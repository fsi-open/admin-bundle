<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminAnnotatedElementPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminElementPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\SetEventDispatcherPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension;
use FSi\Bundle\AdminBundle\Finder\AdminClassFinder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class FSiAdminBundle extends Bundle
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
    }

    /**
     * @return \FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminExtension();
        }

        return $this->extension;
    }
}
