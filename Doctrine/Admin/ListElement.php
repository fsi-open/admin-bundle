<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\BaseListElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element as DoctrineElement;

abstract class ListElement extends BaseListElement implements DoctrineElement
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    protected $indexer;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws RuntimeException
     */
    public function getObjectManager()
    {
        $om = $this->registry->getManagerForClass($this->getClassName());

        if (is_null($om )) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $om;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->getClassName());
    }

    /**
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer()
    {
        return $this->indexer;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->indexer = new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }
}
