<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Doctrine;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use FSi\Bundle\ResourceRepositoryBundle\Exception\EntityRepositoryException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

class ResourceRepository extends EntityRepository implements ResourceValueRepository
{
    /**
     * @param mixed $id
     * @param int $lockMode
     * @param null $lockVersion
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        if ($lockMode === null && \Doctrine\ORM\Version::compare('2.5.0-dev') === 1) {
            $lockMode = LockMode::NONE;
        }
        $resource = parent::find($id, $lockMode, $lockVersion);

        if (!isset($resource)) {
            $resourceClass = $this->getClassName();
            $resource = new $resourceClass();
            $resource->setKey($id);
        }

        return $resource;
    }

    /**
     * @param $key
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue
     */
    public function get($key)
    {
        return $this->find($key);
    }

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function save(ResourceValue $resourceValue)
    {
        $this->_em->persist($resourceValue);
        $this->_em->flush();
    }

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function add(ResourceValue $resourceValue)
    {
        $this->save($resourceValue);
    }

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function remove(ResourceValue $resourceValue)
    {
        $this->_em->remove($resourceValue);
        $this->_em->flush();
    }

    /**
     * Unsupported method
     *
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array|void
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\EntityRepositoryException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        throw new EntityRepositoryException('Method "findBy" is not supported in "FSiResourceRepository:Resource" entity repository');
    }

    /**
     * Unsupported method
     *
     * @return array|void
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\EntityRepositoryException
     */
    public function findAll()
    {
        throw new EntityRepositoryException('Method "findAll" is not supported in "FSiResourceRepository:Resource" entity repository');
    }

    /**
     * Unsupported method
     *
     * @param array $criteria
     * @param array $orderBy
     * @return object|void
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\EntityRepositoryException
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        throw new EntityRepositoryException('Method "findOneBy" is not supported in "FSiResourceRepository:Resource" entity repository');
    }
}
