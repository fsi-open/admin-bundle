<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;

interface ColumnTypeInterface
{
    /**
     * Get column type identity.
     *
     * @return string
     */
    public function getId();

    /**
     * Get name under column is registered in data grid.
     *
     * @return string
     */
    public function getName();

    /**
     * @param \FSi\Component\DataGrid\DataGridInterface $dataGrid
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setDataGrid(DataGridInterface $dataGrid);

    /**
     * @return \FSi\Component\DataGrid\DataGridInterface $dataGrid
     */
    public function getDataGrid();

    /**
     * @param \FSi\Component\DataGrid\DataMapper\DataMapperInterface $dataMapper
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setDataMapper(DataMapperInterface $dataMapper);

    /**
     * Return DataMapper.
     *
     * @return \FSi\Component\DataGrid\DataMapper\DataMapperInterface
     */
    public function getDataMapper();

    /**
     * Filter value before passing it to view.
     *
     * @param mixed $value
     */
    public function filterValue($value);

    /**
     * Get value from object using DataMapper
     *
     * @param mixed $value
     */
    public function getValue($object);

    /**
     * Create CellView object set source value on it.
     *
     * @param mixed  $object
     * @param string $index
     * @return \FSi\Component\DataGrid\Column\CellViewInterface
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException
     */
    public function createCellView($object, $index);

    /**
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     */
    public function buildCellView(CellViewInterface $view);

    /**
     * Create HeaderView object for column.
     *
     * @return \FSi\Component\DataGrid\Column\HeaderViewInterface
     */
    public function createHeaderView();

    /**
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    public function buildHeaderView(HeaderViewInterface $view);

    /**
     * Binds data into object using DataMapper object.
     *
     * @param mixed $data
     * @param mixed $object
     * @param mixed $index
     */
    public function bindData($data, $object, $index);

    /**
     * Sets the default options for this type.
     * To access OptionsResolver use $this->getOptionsResolver()
     * initOptions is called in DataGrid after loading the column type
     * from DataGridFactory.
     */
    public function initOptions();

    /**
     * @param string $name
     * @param mixed $value
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setOption($name, $value);

    /**
     * @param array $options
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setOptions($options);

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * @param string $name
     * @return boolean
     */
    public function hasOption($name);

    /**
     * @param array $extensions
     * @return mixed
     */
    public function setExtensions(array $extensions);

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface $extension
     * @return \FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface
     */
    public function addExtension(ColumnTypeExtensionInterface $extension);

    /**
     * @return array
     */
    public function getExtensions();

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver The options resolver.
     */
    public function getOptionsResolver();
}
