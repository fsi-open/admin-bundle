<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

abstract class BatchElement extends GenericBatchElement implements Element
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * {@inheritdoc}
     */
    public function getObjectManager()
    {
        $om = $this->registry->getManagerForClass($this->getClassName());

        if (is_null($om)) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $om;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->getClassName());
    }

    /**
     * {@inheritdoc}
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer()
    {
        return new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
}
