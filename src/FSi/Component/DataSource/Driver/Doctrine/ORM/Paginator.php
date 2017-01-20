<?php
namespace FSi\Component\DataSource\Driver\Doctrine\ORM;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    public function __construct($query, $fetchJoinCollection = true)
    {
        // Avoid DDC-2213 bug/mistake
        $em = $query->getEntityManager();
        $fetchJoinCollection = true;
        foreach ($query->getRootEntities() as $entity) {
            if ($em->getClassMetadata($entity)->isIdentifierComposite) {
                $fetchJoinCollection = false;
                break;
            }
        }

        parent::__construct($query, $fetchJoinCollection);
    }
}
