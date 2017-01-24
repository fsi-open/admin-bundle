<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
                ->always(function ($v) {
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
                    ->defaultValue(['%locale%'])
                ->end()
                ->scalarNode('menu_config_path')->defaultValue("%kernel.root_dir%/config/admin_menu.yml")->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('@AdminPanel/base.html.twig')->end()
                        ->scalarNode('index_page')->defaultValue('@AdminPanel/Admin/index.html.twig')->end()
                        ->scalarNode('list')->defaultValue('@AdminPanel/List/list.html.twig')->end()
                        ->scalarNode('form')->defaultValue('@AdminPanel/Form/form.html.twig')->end()
                        ->scalarNode('crud_list')->defaultValue('@AdminPanel/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_form')->defaultNull()->end()
                        ->scalarNode('resource')->defaultValue('@AdminPanel/Resource/resource.html.twig')->end()
                        ->scalarNode('display')->defaultValue('@AdminPanel/Display/display.html.twig')->end()
                        ->scalarNode('datagrid_theme')->defaultValue('@AdminPanel/CRUD/datagrid.html.twig')->end()
                        ->scalarNode('datasource_theme')->defaultValue('@AdminPanel/CRUD/datasource.html.twig')->end()
                        ->scalarNode('form_theme')->defaultValue('@AdminPanel/Form/form_theme.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('annotations')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('dirs')
                            ->prototype('scalar')->defaultValue([])->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('data_source')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('yaml_configuration')->defaultTrue()->end()
                        ->arrayNode('twig')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->scalarNode('template')->defaultValue('datasource.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('data_grid')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('yaml_configuration')->defaultTrue()->end()
                        ->arrayNode('twig')
                            ->beforeNormalization()
                                ->ifTrue(function ($v) {
                                    return isset($v['template']);
                                })
                                ->then(function ($v) {
                                    trigger_error('The fsi_admin.data_grid.twig.template configuration key is deprecated since version 1.1 and will be removed in 1.2. Use the fsi_data_grid.twig.themes configuration key instead.', E_USER_DEPRECATED);
                                    return $v;
                                })
                            ->end()
                            ->validate()
                                ->ifTrue(function ($v) {
                                    return isset($v['template']) && ($v['template'] !== null);
                                })
                                ->then(function ($v) {
                                    $v['themes'] = [$v['template']];
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
                                ->defaultValue(['datagrid.html.twig'])
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
