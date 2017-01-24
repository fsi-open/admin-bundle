<?php

declare(strict_types=1);

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
