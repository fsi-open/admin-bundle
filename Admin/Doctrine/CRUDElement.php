<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDInterface;
use FSi\Bundle\AdminBundle\Admin\Doctrine\DoctrineAwareInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class CRUDElement extends AbstractCRUD implements DoctrineAwareInterface, CRUDInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @var \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    protected $indexer;

    /**
     * {@inheritdoc}
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager()
    {
        if (!isset($this->om)) {
            $this->om = $this->registry->getManagerForClass($this->getClassName());
        }

        if (is_null($this->om)) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $this->om;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        if (!isset($this->repository)) {
         $this->repository = $this->getObjectManager()->getRepository($this->getClassName());
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer()
    {
        if (!isset($this->indexer)) {
            $this->indexer = new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
        }

        return $this->indexer;
    }

    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function saveDataGrid()
    {
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
}
