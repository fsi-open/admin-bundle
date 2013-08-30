<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fsi_admin');

        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('@FSiAdmin/base.html.twig')->end()
                        ->scalarNode('index_page')->defaultValue('@FSiAdmin/Admin/index.html.twig')->end()
                        ->scalarNode('crud_list')->defaultValue('@FSiAdmin/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_create')->defaultValue('@FSiAdmin/CRUD/create.html.twig')->end()
                        ->scalarNode('crud_edit')->defaultValue('@FSiAdmin/CRUD/edit.html.twig')->end()
                        ->scalarNode('crud_delete')->defaultValue('@FSiAdmin/CRUD/delete.html.twig')->end()
                        ->scalarNode('datagrid_theme')->defaultValue('@FSiAdmin/CRUD/datagrid.html.twig')->end()
                        ->scalarNode('datasource_theme')->defaultValue('@FSiAdmin/CRUD/datasource.html.twig')->end()
                        ->scalarNode('edit_form_theme')->defaultValue('@FSiAdmin/Form/form_div_layout.html.twig')->end()
                        ->scalarNode('create_form_theme')->defaultValue('@FSiAdmin/Form/form_div_layout.html.twig')->end()
                        ->scalarNode('delete_form_theme')->defaultValue('@FSiAdmin/Form/form_div_layout.html.twig')->end()
                        ->scalarNode('resource_form_theme')->defaultValue('@FSiAdmin/Form/form_div_layout.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
