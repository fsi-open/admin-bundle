<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridExtensionInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * Already registered data grids.
     *
     * @var array
     */
    protected $dataGrids = array();

    /**
     * Currently loaded column types.
     *
     * @var array
     */
    protected $columnTypes = array();

    /**
     * @var \FSi\Component\DataGrid\DataMapper\DataMapperInterface
     */
    protected $dataMapper;

    /**
     * The DataGridExtensionInterface instances
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * @var \FSi\Component\DataGrid\Data\IndexingStrategyInterface
     * @deprecated this field is deprecated and it will be removed in version 1.2
     */
    protected $strategy;

    /**
     * @param array $extensions
     * @param \FSi\Component\DataGrid\DataMapper\DataMapperInterface $dataMapper
     * @param \FSi\Component\DataGrid\Data\IndexingStrategyInterface $strategy
     * @throws \InvalidArgumentException
     */
    public function __construct(array $extensions, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy = null)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof DataGridExtensionInterface) {
                throw new \InvalidArgumentException('Each extension must implement FSi\Component\DataGrid\DataGridExtensionInterface');
            }
        }

        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataGrid($name = 'grid')
    {
        if (array_key_exists($name, $this->dataGrids)) {
            throw new DataGridColumnException(sprintf('Data grid name "%s" is not uniqe, it was used before to create form', $name));
        }

        $this->dataGrids[$name] = true;

        return new DataGrid($name, $this, $this->dataMapper, $this->strategy);
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return true;
        }

        try {
            $this->loadColumnType($type);
        } catch (UnexpectedTypeException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return clone $this->columnTypes[$type];
        }

        $this->loadColumnType($type);

        return clone $this->columnTypes[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexingStrategy()
    {
        return $this->strategy;
    }

    /**
     * Try to load column type from extensions registered in factory.
     *
     * @param string $type
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException
     */
    private function loadColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return;
        }

        $typeInstance = null;
        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnType($type)) {
                $typeInstance = $extension->getColumnType($type);
                break;
            }
        }

        if (!isset($typeInstance)) {
            throw new UnexpectedTypeException(sprintf('There is no column with type "%s" registred in factory.', $type));
        }

        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnTypeExtensions($type)) {
                $columnExtensions = $extension->getColumnTypeExtensions($type);
                foreach ($columnExtensions as $columnExtension) {
                    $typeInstance->addExtension($columnExtension);
                }
            }
        }

        $this->columnTypes[$type] = $typeInstance;
    }
}
