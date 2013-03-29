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
                        ->scalarNode('admin_navigationtop')->defaultValue('@FSiAdmin/Admin/navigationtop.html.twig')->end()
                        ->scalarNode('admin_navigationleft')->defaultValue('@FSiAdmin/Admin/navigationleft.html.twig')->end()
                        ->scalarNode('crud_list')->defaultValue('@FSiAdmin/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_create')->defaultValue('@FSiAdmin/CRUD/create.html.twig')->end()
                        ->scalarNode('crud_edit')->defaultValue('@FSiAdmin/CRUD/edit.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('groups')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('elements')
                            ->isRequired()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
