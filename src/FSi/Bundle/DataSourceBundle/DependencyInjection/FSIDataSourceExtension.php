<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class FSIDataSourceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('datasource.xml');

        $this->registerDrivers($loader);

        if (isset($config['yaml_configuration']) && $config['yaml_configuration']) {
            $loader->load('datasource_yaml_configuration.xml');
        }

        if(isset($config['twig']['enabled']) && $config['twig']['enabled']) {
            $this->registerTwigConfiguration($config['twig'], $container, $loader);
        }
    }

    public function registerTwigConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('twig.xml');
        $container->setParameter('datasource.twig.template', $config['template']);
    }

    /**
     * @param $loader
     */
    private function registerDrivers($loader)
    {
        $loader->load('driver/collection.xml');
        /* doctrine driver is deprecated since version 1.4 */
        $loader->load('driver/doctrine.xml');
        if (class_exists('FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineDriver')) {
            $loader->load('driver/doctrine-orm.xml');
        }
    }
}
