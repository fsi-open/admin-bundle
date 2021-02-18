<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\Manager\Visitor;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Bundle\AdminBundle\Menu\KnpMenu\ItemDecorator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FSIAdminExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('admin.locales', $config['locales']);
        $container->setParameter('admin.default_locale', $config['default_locale']);
        $container->setParameter('admin.menu_config_path', $config['menu_config_path']);

        $container->registerForAutoconfiguration(Worker::class)->addTag('admin.worker');
        $container->registerForAutoconfiguration(ContextInterface::class)->addTag('admin.context');
        $container->registerForAutoconfiguration(Element::class)->addTag('admin.element');
        $container->registerForAutoconfiguration(Visitor::class)->addTag('admin.manager.visitor');
        $container->registerForAutoconfiguration(ItemDecorator::class)->addTag('admin.menu.knp_decorator');

        $this->setTemplateParameters($container, $config['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    protected function setTemplateParameters(ContainerBuilder $container, array $config = []): void
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('admin.templates.%s', $key), $value);
        }
    }
}
