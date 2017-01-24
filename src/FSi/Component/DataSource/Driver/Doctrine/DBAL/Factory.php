<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL;

use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Factory implements DriverFactoryInterface
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
    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
        $this->optionsResolver = new OptionsResolver();
        $this->initOptions();
    }

    /**
     * @return string
     */
    public function getDriverType()
    {
        return 'doctrine-dbal';
    }

    /**
     * @param array $options
     * @return DoctrineDriver
     */
    public function createDriver($options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        return new DoctrineDriver(
            $this->extensions,
            $options['queryBuilder'],
            $options['countField'],
            $options['indexField']
        );
    }

    /**
     * Initialize Options Resolvers for driver and datasource builder.
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function initOptions()
    {
        $this->optionsResolver->setDefaults([
            'connection' => null,
            'queryBuilder' => null,
            'countField' => 'id',
            'indexField' => 'id'
        ]);

        $this->optionsResolver->setAllowedTypes('connection', ['null', '\Doctrine\DBAL\Connection']);
        $this->optionsResolver->setAllowedTypes('queryBuilder', ['null', '\Doctrine\DBAL\Query\QueryBuilder']);
        $this->optionsResolver->setAllowedTypes('countField', ['string']);
        $this->optionsResolver->setAllowedTypes('indexField', ['string']);
    }
}
