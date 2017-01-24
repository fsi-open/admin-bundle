<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL;

use Traversable;

final class Result implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $slice;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @param array $slice
     * @param int $totalCount
     */
    public function __construct(array $slice, $totalCount)
    {
        $this->slice = $slice;
        $this->totalCount = $totalCount;
    }

    /**
     * @return array
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->slice);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->totalCount;
    }
}
