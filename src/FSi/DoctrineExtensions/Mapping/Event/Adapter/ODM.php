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
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Proxy\Proxy;

/**
 * Doctrine event adapter for ODM specific event arguments.
 */
class ODM implements AdapterInterface
{
    /**
     * @var \Doctrine\Common\EventArgs
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
        return 'Document';
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerName()
    {
        return 'ODM';
    }

    /**
     * Extracts identifiers from object or proxy.
     *
     * @param \Doctrine\ODM\MongoDB\DocumentManager $dm
     * @param object $object
     * @param bool $single
     * @return mixed - array or single identifier
     */
    public function extractIdentifier(DocumentManager $dm, $object, $single = true)
    {
        $meta = $dm->getClassMetadata(get_class($object));
        if ($object instanceof Proxy) {
            $id = $dm->getUnitOfWork()->getDocumentIdentifier($object);
        } else {
            $id = ReflectionProperty::factory($meta->name, $meta->identifier)->getValue($object);
        }

        if ($single || !$id) {
            return $id;
        } else {
            return array($meta->identifier => $id);
        }
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
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @param object $object
     * @return array
     */
    public function getObjectChangeSet(UnitOfWork $uow, $object)
    {
        return $uow->getDocumentChangeSet($object);
    }

    /**
     * Get the single identifier field name.
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $meta
     * @return string
     */
    public function getSingleIdentifierFieldName(ClassMetadataInfo $meta)
    {
        return $meta->identifier;
    }

    /**
     * Recompute the single object changeset from a UnitOfWork.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $meta
     * @param object $object
     * @return null
     */
    public function recomputeSingleObjectChangeSet(UnitOfWork $uow, ClassMetadataInfo $meta, $object)
    {
        $uow->recomputeSingleDocumentChangeSet($meta, $object);
    }

    /**
     * Get the scheduled object updates from a UnitOfWork.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectUpdates(UnitOfWork $uow)
    {
        return $uow->getScheduledDocumentUpdates();
    }

    /**
     * Get the scheduled object insertions from a UnitOfWork.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectInsertions(UnitOfWork $uow)
    {
        return $uow->getScheduledDocumentInsertions();
    }

    /**
     * Get the scheduled object deletions from a UnitOfWork.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @return array
     */
    public function getScheduledObjectDeletions(UnitOfWork $uow)
    {
        return $uow->getScheduledDocumentDeletions();
    }

    /**
     * Sets a property value of the original data array of an object.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @param string $oid
     * @param string $property
     * @param mixed $value
     * @return null
     */
    public function setOriginalObjectProperty(UnitOfWork $uow, $oid, $property, $value)
    {
        $uow->setOriginalDocumentProperty($oid, $property, $value);
    }

    /**
     * Clears the property changeset of the object with the given OID.
     *
     * @param \Doctrine\ODM\MongoDB\UnitOfWork $uow
     * @param string $oid The object's OID.
     */
    public function clearObjectChangeSet(UnitOfWork $uow, $oid)
    {
        $uow->clearDocumentChangeSet($oid);
    }
}
