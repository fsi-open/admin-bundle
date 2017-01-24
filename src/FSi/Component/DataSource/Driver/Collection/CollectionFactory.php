<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Collection;

use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * {@inheritdoc}
 */
class CollectionFactory implements DriverFactoryInterface
{
    /**
     * Array of extensions.
     *
     * @var array
     */
    private $extensions;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param array $extensions
     */
    public function __construct($extensions = [])
    {
        $this->extensions = $extensions;
        $this->optionsResolver = new OptionsResolver();
        $this->initOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverType()
    {
        return 'collection';
    }

    /**
     * Creates driver.
     *
     * @param array $options
     * @return \FSi\Component\DataSource\Driver\Collection\CollectionDriver
     */
    public function createDriver($options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        return new CollectionDriver($this->extensions, $options['collection']);
    }

    /**
     * Initialize Options Resolvers for driver and datasource builder.
     */
    private function initOptions()
    {
        $this->optionsResolver->setDefaults([
            'collection' => [],
        ]);

        $this->optionsResolver->setAllowedTypes('collection', 'array');
    }
}
