<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

class DoctrineResult extends ArrayCollection
{
    /**
     * @var int
     */
    private $count;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     * @param \Doctrine\ORM\Tools\Pagination\Paginator $paginator
     */
    public function __construct(ManagerRegistry $registry, DoctrinePaginator $paginator)
    {
        $result = [];
        $this->count = $paginator->count();
        $data = $paginator->getIterator();

        if (count($data)) {
            $firstElement = current($data);
            $dataIndexer =  is_object($firstElement)
                ? new DoctrineDataIndexer($registry, get_class($firstElement))
                : null;

            foreach ($data as $key => $element) {
                $index = isset($dataIndexer) ? $dataIndexer->getIndex($element) : $key;
                $result[$index] = $element;
            }
        }

        parent::__construct($result);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }
}
