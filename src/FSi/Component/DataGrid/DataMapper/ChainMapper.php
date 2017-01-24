<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\DataMapper;

use FSi\Component\DataGrid\Exception\DataMappingException;

class ChainMapper implements DataMapperInterface
{
    /**
     * @var array
     */
    protected $mappers = [];

    /**
     * @param array $mappers
     * @throws \InvalidArgumentException
     */
    public function __construct(array $mappers)
    {
        if (!count($mappers)) {
            throw new \InvalidArgumentException('There must be at least one mapper in chain.');
        }

        foreach ($mappers as $mapper) {
            if (!$mapper instanceof DataMapperInterface) {
                throw new \InvalidArgumentException('Mapper needs to implement FSi\Component\DataGrid\DataMapper\DataMapperInterface');
            }
            $this->mappers[] = $mapper;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($field, $object)
    {
        $data = null;
        $dataFound = false;
        $lastMsg = null;
        foreach ($this->mappers as $mapper) {
            try {
                $data = $mapper->getData($field, $object);
            } catch (DataMappingException $e) {
                $data = null;
                $lastMsg = $e->getMessage();
                continue;
            }

            $dataFound = true;
            break;
        }

        if (!$dataFound) {
            if (!isset($lastMsg)) {
                $lastMsg = sprintf('Cant find any data that fit "%s" field.', $field);
            }
            throw new DataMappingException($lastMsg);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($field, $object, $value)
    {
        $data = null;
        $dataChanged = false;
        $lastMsg = null;

        foreach ($this->mappers as $mapper) {
            try {
                $mapper->setData($field, $object, $value);
            } catch (DataMappingException $e) {
                $lastMsg = $e->getMessage();
                continue;
            }

            $dataChanged = true;
            break;
        }

        if (!$dataChanged) {
            if (!isset($lastMsg)) {
                $lastMsg = sprintf('Cant find any data that fit "%s" field.', $field);
            }

            throw new DataMappingException($lastMsg);
        }

        return true;
    }
}
