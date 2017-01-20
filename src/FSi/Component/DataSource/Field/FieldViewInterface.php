<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Field;

use FSi\Component\DataSource\DataSourceViewInterface;
use FSi\Component\DataSource\Field\FieldTypeInterface;
use FSi\Component\DataSource\Util\AttributesContainerInterface;

/**
 * View of field, responsible for keeping some options needed during view rendering.
 */
interface FieldViewInterface extends AttributesContainerInterface
{
    /**
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function __construct(FieldTypeInterface $field);

    /**
     * Return field's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Return field's type.
     *
     * @return string
     */
    public function getType();

    /**
     * Returns type of comparison for this field
     *
     * @return string
     */
    public function getComparison();

    /**
     * Returns current parameter value bound to this field
     *
     * @return string
     */
    public function getParameter();

    /**
     * Sets DataSource view.
     *
     * @param \FSi\Component\DataSource\DataSourceViewInterface $dataSourceView
     */
    public function setDataSourceView(DataSourceViewInterface $dataSourceView);

    /**
     * Return assigned DataSource view.
     *
     * @return \FSi\Component\DataSource\DataSourceViewInterface
     */
    public function getDataSourceView();
}
