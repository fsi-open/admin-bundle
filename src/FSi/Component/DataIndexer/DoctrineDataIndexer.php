<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataIndexer;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Component\DataIndexer\Exception\InvalidArgumentException;
use FSi\Component\DataIndexer\Exception\RuntimeException;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineDataIndexer implements DataIndexerInterface
{
    /**
     * @var string
     */
    protected $separator = "|";

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param ManagerRegistry $registry
     * @param $class
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function __construct(ManagerRegistry $registry, $class)
    {
        $this->manager = $this->tryToGetObjectManager($registry, $class);
        $this->class = $this->tryToGetRootClass($class);
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex($data)
    {
        $this->validateData($data);

        return $this->joinIndexParts($this->getIndexParts($data));
    }

    /**
     * {@inheritdoc}
     */
    public function getData($index)
    {
        return $this->tryToFindEntity($this->buildSearchCriteria($index));
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSlice($indexes)
    {
        $this->validateIndexes($indexes);

        return $this->getRepository()
            ->findBy($this->buildMultipleSearchCriteria($indexes));
    }

    /**
     * {@inheritdoc}
     */
    public function validateData($data)
    {
        if (!is_object($data)) {
            throw new InvalidArgumentException("DoctrineDataIndexer can index only objects.");
        }

        if (!is_a($data, $this->class)) {
            throw new InvalidArgumentException(sprintf(
                'DoctrineDataIndexer expects data as instance of "%s" instead of "%s".',
                $this->class,
                get_class($data)
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Get class idexer is constructed for.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Returns an array of identifier field names for self::$class.
     *
     * @return array
     */
    private function getIdentifierFieldNames()
    {
        return $this->manager
            ->getClassMetadata($this->class)
            ->getIdentifierFieldNames();
    }

    /**
     * @param ManagerRegistry $registry
     * @param $class
     * @return ObjectManager
     * @throws Exception\InvalidArgumentException
     */
    private function tryToGetObjectManager(ManagerRegistry $registry, $class)
    {
        $manager = $registry->getManagerForClass($class);

        if (!isset($manager)) {
            throw new InvalidArgumentException(sprintf(
                'ManagerRegistry doesn\'t have manager for class "%s".',
                $class
            ));
        }

        return $manager;
    }

    /**
     * @param $class
     * @return string
     * @throws Exception\RuntimeException
     */
    private function tryToGetRootClass($class)
    {
        $classMetadata = $this->manager->getClassMetadata($class);

        if (!$classMetadata instanceof ClassMetadataInfo) {
            throw new RuntimeException("Only Doctrine ORM is supported at the moment");
        }

        if ($classMetadata->isMappedSuperclass) {
            throw new RuntimeException('DoctrineDataIndexer can\'t be created for mapped super class.');
        }

        return $classMetadata->rootEntityName;
    }

    /**
     * @param $object
     * @return array
     */
    private function getIndexParts($object)
    {
        $identifiers = $this->getIdentifierFieldNames();

        $accessor = PropertyAccess::createPropertyAccessor();
        $indexes = array_map(
            function ($identifier) use ($object, $accessor) {
                return $accessor->getValue($object, $identifier);
            },
            $identifiers
        );

        return $indexes;
    }

    /**
     * @param $indexes
     * @return string
     */
    private function joinIndexParts($indexes)
    {
        return implode($this->separator, $indexes);
    }

    /**
     * @param $index
     * @param $identifiersCount
     * @return array
     * @throws Exception\RuntimeException
     */
    private function splitIndex($index, $identifiersCount)
    {
        $indexParts = explode($this->getSeparator(), $index);
        if (count($indexParts) != $identifiersCount) {
            throw new RuntimeException("Can't split index into parts. Maybe you should consider using different separator?");
        }

        return $indexParts;
    }

    /**
     * @param $indexes
     * @return array
     */
    private function buildMultipleSearchCriteria($indexes)
    {
        $multipleSearchCriteria = array();
        foreach ($indexes as $index) {
            foreach ($this->buildSearchCriteria($index) as $identifier => $indexPart) {
                if (!array_key_exists($identifier, $multipleSearchCriteria)) {
                    $multipleSearchCriteria[$identifier] = array();
                }

                $multipleSearchCriteria[$identifier][] = $indexPart;
            }
        }
        return $multipleSearchCriteria;
    }

    /**
     * @param $index
     * @return array
     */
    private function buildSearchCriteria($index)
    {
        $identifiers = $this->getIdentifierFieldNames();
        $indexParts = $this->splitIndex($index, count($identifiers));

        return array_combine($identifiers, $indexParts);
    }

    /**
     * @param $searchCriteria
     * @return object
     * @throws Exception\RuntimeException
     */
    private function tryToFindEntity($searchCriteria)
    {
        $entity = $this->getRepository()->findOneBy($searchCriteria);

        if (!isset($entity)) {
            throw new RuntimeException('Can\'t find any entity using the following search criteria: "' . implode(", ", $searchCriteria) . '"');
        }

        return $entity;
    }

    /**
     * @param $indexes
     * @throws Exception\InvalidArgumentException
     */
    private function validateIndexes($indexes)
    {
        if (!is_array($indexes) && (!$indexes instanceof \Traversable && !$indexes instanceof \Countable)) {
            throw new InvalidArgumentException('Indexes are not traversable.');
        }
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository()
    {
        return $this->manager->getRepository($this->class);
    }
}
