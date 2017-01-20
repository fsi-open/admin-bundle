<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        return array();
    }
}
