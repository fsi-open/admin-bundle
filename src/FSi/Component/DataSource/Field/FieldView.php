<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Field;

use FSi\Component\DataSource\DataSourceViewInterface;
use FSi\Component\DataSource\Util\AttributesContainer;

/**
 * {@inheritdoc}
 */
class FieldView extends AttributesContainer implements FieldViewInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $comparison;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var DataSourceViewInterface
     */
    private $dataSourceView;

    /**
     * {@inheritdoc}
     */
    public function __construct(FieldTypeInterface $field)
    {
        $this->name = $field->getName();
        $this->type = $field->getType();
        $this->comparison = $field->getComparison();
        $this->parameter = $field->getCleanParameter();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceView(DataSourceViewInterface $dataSourceView)
    {
        $this->dataSourceView = $dataSourceView;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSourceView()
    {
        return $this->dataSourceView;
    }
}
