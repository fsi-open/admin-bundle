<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fsi_resource_repository');

        $supportedDrivers = ['orm'];

        $rootNode->children()
            ->scalarNode('db_driver')
                ->defaultValue('orm')
                ->validate()
                    ->ifNotInArray($supportedDrivers)
                    ->thenInvalid('The driver %s is not supported. Please choose one of ' . implode(', ', $supportedDrivers))
                ->end()
                ->cannotBeOverwritten()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('map_path')->defaultValue('%kernel.root_dir%/config/resource_map.yml')->end()
            ->scalarNode('resource_class')->isRequired()->cannotBeEmpty()->end();

        return $treeBuilder;
    }
}
