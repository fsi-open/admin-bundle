<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $result = array();
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
