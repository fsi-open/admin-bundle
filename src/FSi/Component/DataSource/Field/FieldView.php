<?php

declare(strict_types=1);

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
