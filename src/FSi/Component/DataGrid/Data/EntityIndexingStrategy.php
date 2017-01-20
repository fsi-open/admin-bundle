<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Data;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use Doctrine\Common\Persistence\Mapping\MappingException;

class EntityIndexingStrategy implements IndexingStrategyInterface
{
    /**
     * @var string
     */
    protected $separator;

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->separator = '|';
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex($object, DataMapperInterface $dataMapper)
    {
        if (!is_object($object)) {
            return null;
        }

        $class = get_class($object);
        $em = $this->registry->getManagerForClass($class);
        if (!$em) {
            return null;
        }
        $metadataFactory = $em->getMetadataFactory();
        $metadata = $metadataFactory->getMetadataFor($class);

        $identifiers = $metadata->getIdentifierFieldNames();

        if (!is_array($identifiers)) {
            throw new \RuntimeException('Entity indexing strategy can\'t resolve the index from object.');
        }

        $indexes = array();
        foreach ($identifiers as $identifier) {
            $indexes[] = $dataMapper->getData($identifier, $object);
        }

        return implode($this->separator, $indexes);
    }

    /**
     * {@inheritdoc}
     */
    public function revertIndex($index, $dataType)
    {
        $em = $this->registry->getManagerForClass($dataType);
        $metadataFactory = $em->getMetadataFactory();
        $metadata = $metadataFactory->getMetadataFor($dataType);
        $identifiers = $metadata->getIdentifierFieldNames();

        if (count($identifiers) == 1) {
            $key = current($identifiers);

            return array($key => $index);
        }

        $indexPieces = explode($this->separator, $index);

        if (count($indexPieces) != count($identifiers)) {
            throw new \RuntimeException(sprintf('Entity indexing strategy can\'t revert the index: "%s"', $index));
        }

        $reverted = array();

        foreach ($indexPieces as $pos => $piece) {
            if (!isset($identifiers[$pos])) {
                throw new \RuntimeException(sprintf('Entity indexing strategy can\'t revert the index: "%s"', $index));
            }

            $reverted[$identifiers[$pos]] = $piece;
        }

        return $reverted;
    }

    /**
     * {@inheritdoc}
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }
}
