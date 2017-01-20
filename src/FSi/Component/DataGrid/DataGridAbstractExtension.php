<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\DataGridException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class DataGridAbstractExtension implements DataGridExtensionInterface
{
    /**
     * All column types extensions provided by data grid extension.
     *
     * @var array
     */
    protected $columnTypesExtensions;

    /**
     * All column types provided by extension.
     *
     * @var array
     */
    protected $columnTypes;

    /**
     * Returns a column type by id (all column types mush have unique id).
     *
     * @param string $id The identity of the column type
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface The column type
     * @throws \FSi\Component\DataGrid\Exception\DataGridException if the given column type is not a part of this extension
     */
    public function getColumnType($type)
    {
        if (!isset($this->columnTypes)) {
            $this->initColumnTypes();
        }

        if (!isset($this->columnTypes[$type])) {
            throw new DataGridException(sprintf('The column type "%s" can not be loaded by this extension', $type));
        }

        return $this->columnTypes[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($type)
    {
        if (!isset($this->columnTypes)) {
            $this->initColumnTypes();
        }

        return isset($this->columnTypes[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnTypeExtensions($type)
    {
        if (!isset($this->columnTypesExtensions)) {
            $this->initColumnTypesExtensions();
        }

        return isset($this->columnTypesExtensions[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnTypeExtensions($type)
    {
        if (!isset($this->columnTypesExtensions)) {
            $this->initColumnTypesExtensions();
        }

        if (!isset($this->columnTypesExtensions[$type])) {
            throw new DataGridException(sprintf('Extension for column type "%s" can not be loaded by this data grid extension', $type));
        }

        return $this->columnTypesExtensions[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function registerSubscribers(DataGridInterface $dataGrid)
    {
        $subscribers = $this->loadSubscribers();
        if (!is_array($subscribers)) {
            throw new UnexpectedTypeException('Listeners needs to be stored in array.');
        }

        foreach ($subscribers as $subscriber) {
            if (!$subscriber instanceof EventSubscriberInterface) {
                throw new UnexpectedTypeException(sprintf('"%s" is not instance of Symfony\Component\EventDispatcher\EventSubscriberInterface', $columnType));
            }

            $dataGrid->addEventSubscriber($subscriber);
        }
    }

    /**
     * If extension needs to provide new column types this function
     * should be overloaded in child class and return array of DataGridColumnTypeInterface
     * instances.
     *
     * @return array
     */
    protected function loadColumnTypes()
    {
        return array();
    }

    /**
     * If extension needs to load event subscribers this method should be overloaded in
     * child class and return array event subscribers.
     *
     * @return array
     */
    protected function loadSubscribers()
    {
        return array();
    }

    /**
     * If extension needs to provide new column types this function
     * should be overloaded in child class and return array of DataGridColumnTypeInterface
     * instances.
     *
     * @return array
     */
    protected function loadColumnTypesExtensions()
    {
        return array();
    }

    /**
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException
     */
    private function initColumnTypes()
    {
        $this->columnTypes = array();

        $columnTypes = $this->loadColumnTypes();

        foreach ($columnTypes as $columnType) {
            if (!$columnType instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException('Column Type must implement FSi\Component\DataGrid\Column\ColumnTypeInterface');
            }

            $this->columnTypes[$columnType->getId()] = $columnType;
        }
    }

    /**
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException
     */
    private function initColumnTypesExtensions()
    {
        $columnTypesExtensions = $this->loadColumnTypesExtensions();
        foreach ($columnTypesExtensions as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException('Extension must implement FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
            }

            $types = $extension->getExtendedColumnTypes();
            foreach ($types as $type) {
                if (!isset($this->columnTypesExtensions)) {
                    $this->columnTypesExtensions[$type] = array();
                }
                $this->columnTypesExtensions[$type][] = $extension;
            }
        }
    }
}
