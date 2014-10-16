<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class ResourceElement extends GenericResourceElement implements Element
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return string
     */
    abstract public function getClassName();

    /**
     * @return \FSi\Bundle\ResourceRepositoryBundle\Doctrine\ResourceRepository
     */
    public function getRepository()
    {
        $repository = $this->registry->getRepository($this->getClassName());

        if (!$repository instanceof ResourceValueRepository) {
            throw new RuntimeException(sprintf(
                'Repository for class %s must implement \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository',
                $this->getClassName()
            ));
        }

        return $repository;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
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
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resource
     */
    public function save(ResourceValue $resource)
    {
        $this->getObjectManager()->persist($resource);
        $this->getObjectManager()->flush();
    }
}
