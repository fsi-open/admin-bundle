<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Collection\Extension\Core;

use FSi\Component\DataSource\Driver\DriverAbstractExtension;

/**
 * Core extension for Doctrine driver.
 */
class CoreExtension extends DriverAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return ['collection'];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes()
    {
        return [
            new Field\Text(),
            new Field\Number(),
            new Field\Date(),
            new Field\Time(),
            new Field\DateTime(),
            new Field\Boolean(),
        ];
    }
}
