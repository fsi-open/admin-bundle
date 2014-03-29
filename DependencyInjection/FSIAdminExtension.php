<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class FSIAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('admin.display_language_switch', $config['display_language_switch']);
        $container->setParameter('admin.menu_config_path', $config['menu_config_path']);
        $container->setParameter('admin.elements.dirs', $config['annotations']['dirs']);

        $this->setTemplateParameters($container, $config['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['display_language_switch']) {
            $loader->load('locale_listener.xml');
        }

        $loader->load('context/list.xml');
        $loader->load('context/create.xml');
        $loader->load('context/edit.xml');
        $loader->load('context/delete.xml');
        $loader->load('context/resource.xml');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     */
    protected function setTemplateParameters(ContainerBuilder $container, $config = array())
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('admin.templates.%s', $key), $value);
        }
    }
}
