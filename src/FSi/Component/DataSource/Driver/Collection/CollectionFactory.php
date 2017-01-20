<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function __construct($extensions = array())
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
    public function createDriver($options = array())
    {
        $options = $this->optionsResolver->resolve($options);

        return new CollectionDriver($this->extensions, $options['collection']);
    }

    /**
     * Initialize Options Resolvers for driver and datasource builder.
     */
    private function initOptions()
    {
        $this->optionsResolver->setDefaults(array(
            'collection' => array(),
        ));

        $this->optionsResolver->setAllowedTypes('collection', 'array');
    }
}
