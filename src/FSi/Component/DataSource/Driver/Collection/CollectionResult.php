<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class CollectionResult extends ArrayCollection
{
    /**
     * @var int
     */
    private $count;

    /**
     * @param array $elements
     * @param \Doctrine\Common\Collections\Criteria $criteria
     */
    public function __construct(array $elements = [], Criteria $criteria)
    {
        $collection = new ArrayCollection($elements);
        $firstResult = $criteria->getFirstResult();
        $maxResults = $criteria->getMaxResults();
        $criteria->setFirstResult(null);
        $criteria->setMaxResults(null);
        $collection = $collection->matching($criteria);
        $this->count = $collection->count();
        parent::__construct($collection->slice($firstResult, $maxResults));
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }
}
