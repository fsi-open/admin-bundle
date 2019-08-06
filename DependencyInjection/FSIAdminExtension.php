<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection;

use FSi\Bundle\AdminBundle\Admin\Element;
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
        $container->setParameter('admin.elements.dirs', $config['annotations']['dirs']);
        $container->registerForAutoconfiguration(Element::class)->addTag('admin.element');

        $this->setTemplateParameters($container, $config['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('datagrid.xml');
        $loader->load('menu.xml');
        $loader->load('knp-menu.xml');

        $loader->load('locale_listener.xml');

        $loader->load('context/list.xml');
        $loader->load('context/form.xml');
        $loader->load('context/batch.xml');
        $loader->load('context/display.xml');
    }

    protected function setTemplateParameters(ContainerBuilder $container, array $config = []): void
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('admin.templates.%s', $key), $value);
        }
    }
}
