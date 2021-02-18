<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle;

use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ContextPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\KnpMenuBuilderPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ManagerVisitorPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ResourceRepositoryPass());
        $container->addCompilerPass(new TwigGlobalsPass());
    }

    public function getContainerExtension(): FSIAdminExtension
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminExtension();
        }

        return $this->extension;
    }
}
