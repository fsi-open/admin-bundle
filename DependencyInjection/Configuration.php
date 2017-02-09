<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fsi_admin');

        $rootNode
            ->validate()
                ->always(function($v) {
                    if (!isset($v['templates']['crud_list'])) {
                        $v['templates']['crud_list'] = $v['templates']['list'];
                    }
                    if (!isset($v['templates']['crud_form'])) {
                        $v['templates']['crud_form'] = $v['templates']['form'];
                    }
                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('default_locale')->defaultValue('%locale%')->end()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                    ->defaultValue(array('%locale%'))
                ->end()
                ->scalarNode('menu_config_path')->defaultValue("%kernel.root_dir%/config/admin_menu.yml")->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('@FSiAdmin/base.html.twig')->end()
                        ->scalarNode('index_page')->defaultValue('@FSiAdmin/Admin/index.html.twig')->end()
                        ->scalarNode('list')->defaultValue('@FSiAdmin/List/list.html.twig')->end()
                        ->scalarNode('form')->defaultValue('@FSiAdmin/Form/form.html.twig')->end()
                        ->scalarNode('crud_list')->defaultValue('@FSiAdmin/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_form')->defaultValue('@FSiAdmin/CRUD/form.html.twig')->end()
                        ->scalarNode('resource')->defaultValue('@FSiAdmin/Resource/resource.html.twig')->end()
                        ->scalarNode('display')->defaultValue('@FSiAdmin/Display/display.html.twig')->end()
                        ->scalarNode('datagrid_theme')->defaultValue('@FSiAdmin/CRUD/datagrid.html.twig')->end()
                        ->scalarNode('datasource_theme')->defaultValue('@FSiAdmin/CRUD/datasource.html.twig')->end()
                        ->scalarNode('form_theme')->defaultValue('@FSiAdmin/Form/form_theme.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('annotations')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('dirs')
                            ->prototype('scalar')->defaultValue(array())->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
