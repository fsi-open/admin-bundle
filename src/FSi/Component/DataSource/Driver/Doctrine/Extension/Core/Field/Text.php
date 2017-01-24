<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Text as BaseText;

/**
 * Text field.
 * @deprecated since version 1.2
 */
class Text extends BaseText implements DoctrineFieldInterface
{
}
