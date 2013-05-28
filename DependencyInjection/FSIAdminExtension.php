<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class FSIAdminExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setGroupElementOptions($container, $config['groups']);
        $this->setTemplateParameters($container, $config['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setGroupElementOptions(ContainerBuilder $container, $config = array())
    {
        $groups = array();
        foreach ($config as $groupid => $group) {
            $groups[$groupid] = array();
            foreach ($group['elements'] as $elementid => $element) {
                $groups[$groupid][$elementid] = $element['options'];
            }
        }

        $container->setParameter('admin.groups', $groups);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setTemplateParameters(ContainerBuilder $container, $config = array())
    {
        $container->setParameter('admin.templates.base', $config['base']);
        $container->setParameter('admin.templates.datagrid_theme', $config['datagrid_theme']);
        $container->setParameter('admin.templates.datasource_theme', $config['datasource_theme']);
        $container->setParameter('admin.templates.create_form_theme', $config['create_form_theme']);
        $container->setParameter('admin.templates.edit_form_theme', $config['edit_form_theme']);
        $container->setParameter('admin.templates.delete_form_theme', $config['delete_form_theme']);

        $container->setParameter('admin.templates.index_page', $config['index_page']);
        $container->setParameter('admin.templates.admin_navigationtop', $config['admin_navigationtop']);
        $container->setParameter('admin.templates.admin_navigationleft', $config['admin_navigationleft']);

        $container->setParameter('admin.templates.crud_list', $config['crud_list']);
        $container->setParameter('admin.templates.crud_create', $config['crud_create']);
        $container->setParameter('admin.templates.crud_edit', $config['crud_edit']);
        $container->setParameter('admin.templates.crud_delete', $config['crud_delete']);
    }
}