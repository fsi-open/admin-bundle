<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Date as BaseDate;

/**
 * Date field.
 * @deprecated since version 1.2
 */
class Date extends BaseDate implements DoctrineFieldInterface
{
}
