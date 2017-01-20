<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping\Event\Adapter;

use FSi\Component\Reflection\ReflectionProperty;
use FSi\DoctrineExtensions\Mapping\Event\AdapterInterface;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Proxy\Proxy;

/**
 * Doctrine event adapter for ORM specific event arguments.
 */
class ORM implements AdapterInterface
{
    /**
     * @var \Doctrine\Common\EventArgs;
     */
    private $args;

    /**
     * {@inheritdoc}
     */
    public function setEventArgs(EventArgs $args)
    {
        $this->args = $args;
    }

    /**
     * {@inheritdoc}
     */
    public function getDomainObjectName()
    {
        return 'Entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerName()
    {
        return 'ORM';
    }

    /**
     * Extracts identifiers from object or proxy.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param object $object
     * @param bool $single
     * @return mixed - array or single identifier
     */
    public function extractIdentifier(EntityManager $em, $object, $single = true)
    {
        if ($object instanceof Proxy) {
            $id = $em->getUnitOfWork()->getEntityIdentifier($object);
        } else {
            $meta = $em->getClassMetadata(get_class($object));
            $id = array();
            foreach ($meta->identifier as $name) {
                $id[$name] = ReflectionProperty::factory($meta->name, $name)->getValue($object);
                // return null if one of identifiers is missing
                if (!$id[$name]) {
                    return null;
                }
            }
        }

        if ($single) {
            $id = current($id);
        }
        return $id;
    }

    /**
     * Call event specific method.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $method = str_replace('Object', $this->getDomainObjectName(), $method);
        return call_user_func_array(array($this->args, $method), $args);
    }

    /**
     * Get the object changeset from a UnitOfWork.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @param object $object
     * @return array
     */
    public function getObjectChangeSet(UnitOfWork $uow, $object)
    {
        return $uow->getEntityChangeSet($object);
    }

    /**
     * Get the single identifier field name.
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $meta
     * @return string
     */
    public function getSingleIdentifierFieldName(ClassMetadataInfo $meta)
    {
        return $meta->getSingleIdentifierFieldName();
    }

    /**
     * Recompute the single object changeset from a UnitOfWork.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $meta
     * @param object $object
     * @return null
     */
    public function recomputeSingleObjectChangeSet(UnitOfWork $uow, ClassMetadataInfo $meta, $object)
    {
        $uow->recomputeSingleEntityChangeSet($meta, $object);
    }

    /**
     * Get the scheduled object updates from a UnitOfWork.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectUpdates(UnitOfWork $uow)
    {
        return $uow->getScheduledEntityUpdates();
    }

    /**
     * Get the scheduled object insertions from a UnitOfWork.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectInsertions(UnitOfWork $uow)
    {
        return $uow->getScheduledEntityInsertions();
    }

    /**
     * Get the scheduled object deletions from a UnitOfWork.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectDeletions(UnitOfWork $uow)
    {
        return $uow->getScheduledEntityDeletions();
    }

    /**
     * Sets a property value of the original data array of an object.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @param string $oid
     * @param string $property
     * @param mixed $value
     * @return null
     */
    public function setOriginalObjectProperty(UnitOfWork $uow, $oid, $property, $value)
    {
        $uow->setOriginalEntityProperty($oid, $property, $value);
    }

    /**
     * Clears the property changeset of the object with the given OID.
     *
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @param string $oid The object's OID.
     */
    public function clearObjectChangeSet(UnitOfWork $uow, $oid)
    {
        $uow->clearEntityChangeSet($oid);
    }
}
