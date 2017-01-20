<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField;
use Doctrine\DBAL\Types\Type;

/**
 * Boolean field.
 */
class Boolean extends DoctrineAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq');

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function getDBALType()
    {
        return Type::BOOLEAN;
    }
}
