<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle;

use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\FlashMessagesPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\ResourceRepositoryPass;
use FSi\Bundle\AdminBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new FlashMessagesPass());
        $container->addCompilerPass(new ResourceRepositoryPass());
        $container->addCompilerPass(new TwigGlobalsPass());

        if (
            true === $container->hasExtension('fsi_translatable')
            && true === $container->hasExtension('fsi_resource_repository')
        ) {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
            $loader->load('resource_repository_translatable.xml');
        }
    }

    public function getContainerExtension(): FSIAdminExtension
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminExtension();
        }

        return $this->extension;
    }
}
