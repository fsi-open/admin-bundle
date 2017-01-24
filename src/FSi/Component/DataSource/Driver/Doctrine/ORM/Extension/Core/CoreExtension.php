<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core;

use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineFieldInterface;
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
        return ['doctrine-orm'];
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
            new Field\Entity(),
            new Field\Boolean(),
        ];
    }
}
