<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use FSi\Component\DataIndexer\DoctrineDataIndexer;

trait DataIndexerElementImpl
{
    use ElementImpl;

    /**
     * @return \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    public function getDataIndexer()
    {
        return new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }
}
