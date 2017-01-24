<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Symfony\Core;

use FSi\Component\DataSource\DataSourceAbstractExtension;

/**
 * Main extension for all Symfony based extensions. Its main purpose is to
 * replace binded Request object into array.
 */
class CoreExtension extends DataSourceAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new EventSubscriber\BindParameters(),
        ];
    }
}
