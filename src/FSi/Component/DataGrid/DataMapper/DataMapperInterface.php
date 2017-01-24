<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\DataMapper;

interface DataMapperInterface
{
    /**
     * Get data from object for specified column type.
     *
     * @param string $field
     * @param mixed $object
     * @return boolean - return false if can't get value from object
     * @throws \FSi\Component\DataGrid\Exception\DataMappingException - thrown when mapper cant fit any object data into column
     */
    public function getData($field, $object);

    /**
     * Sets data to object for specified column type.
     *
     * @param string $field
     * @param mixed $object
     * @param mixed $value
     * @return boolean - return true if value was correctly changed
     * @throws \FSi\Component\DataGrid\Exception\DataMappingException - thrown when mapper cant fit any object data into column
     */
    public function setData($field, $object, $value);
}
