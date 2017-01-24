<?php

declare(strict_types=1);

namespace FSi\Component\DataSource;

/**
 * {@inheritdoc}
 */
abstract class DataSourceAbstractExtension implements DataSourceExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        return [];
    }
}
