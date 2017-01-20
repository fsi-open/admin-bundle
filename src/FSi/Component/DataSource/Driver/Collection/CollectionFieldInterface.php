<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Collection;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface for Doctrine driver's fields.
 */
interface CollectionFieldInterface
{
    /**
     * Builds criteria.
     *
     * @param \Doctrine\Common\Collections\Criteria $c
     */
    public function buildCriteria(Criteria $c);

    /**
     * Returns PHP type that this field's value will be casted to before comparisons.
     *
     * @return null|string
     */
    public function getPHPType();
}
