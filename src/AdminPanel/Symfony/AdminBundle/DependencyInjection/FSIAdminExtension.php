<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection;

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

        $container->setParameter('admin.locales', $config['locales']);
        $container->setParameter('admin.default_locale', $config['default_locale']);
        $container->setParameter('admin.menu_config_path', $config['menu_config_path']);
        $container->setParameter('admin.elements.dirs', $config['annotations']['dirs']);

        $this->setTemplateParameters($container, $config['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('datagrid.xml');
        $loader->load('menu.xml');
        $loader->load('knp-menu.xml');

        $loader->load('locale_listener.xml');

        $loader->load('context/list.xml');
        $loader->load('context/form.xml');
        $loader->load('context/batch.xml');
        $loader->load('context/display.xml');

        if (isset($config['data_grid']['yaml_configuration']) && $config['data_grid']['yaml_configuration']) {
            $loader->load('datagrid_yaml_configuration.xml');
        }

        if (isset($config['data_grid']['twig']['enabled']) && $config['data_grid']['twig']['enabled']) {
            $this->registerDataGridTwigConfiguration($config['data_grid']['twig'], $container, $loader);
        }
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @param XmlFileLoader $loader
     */
    protected function registerDataGridTwigConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('twig.xml');
        $container->setParameter('datagrid.twig.themes', $config['themes']);
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
