<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $rootNode = $tb->root('fsi_data_grid');
        $rootNode
            ->children()
                ->booleanNode('yaml_configuration')->defaultTrue()->end()
                ->arrayNode('twig')
                    ->beforeNormalization()
                        ->ifTrue(function ($v) { return isset($v['template']); })
                        ->then(function ($v) {
                            trigger_error('The fsi_data_grid.twig.template configuration key is deprecated since version 1.1 and will be removed in 1.2. Use the fsi_data_grid.twig.themes configuration key instead.', E_USER_DEPRECATED);
                            return $v;
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return isset($v['template']) && ($v['template'] !== null);
                        })
                        ->then(function ($v) {
                            $v['themes'] = array($v['template']);
                            unset($v['template']);
                            return $v;
                        })
                    ->end()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('template')->end()
                        ->arrayNode('themes')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('datagrid.html.twig'))
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $tb;
    }
}
