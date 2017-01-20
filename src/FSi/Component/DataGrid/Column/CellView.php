<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridViewInterface;

class CellView implements CellViewInterface
{
    /**
     * The original object from which the value of the cell was retrieved.
     *
     * @var mixed
     */
    protected $source;

    /**
     * Cell value. In most cases this should be a simple string.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Cell attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Cell name.
     *
     * @var string
     */
    protected $name;

    /**
     * Cell type.
     *
     * @var string
     */
    protected $type;

    /**
     * @var DataGridViewInterface
     */
    protected $datagrid;

    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
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
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridView(DataGridViewInterface $dataGrid)
    {
        $this->datagrid = $dataGrid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGridView()
    {
        return $this->datagrid;
    }
}
