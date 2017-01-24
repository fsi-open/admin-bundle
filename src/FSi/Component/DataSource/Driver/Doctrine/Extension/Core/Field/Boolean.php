<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Boolean as BaseField;

/**
 * Boolean field.
 * @deprecated since version 1.2
 */
class Boolean extends BaseField implements DoctrineFieldInterface
{
}
