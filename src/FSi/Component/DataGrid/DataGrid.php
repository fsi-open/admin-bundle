<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridView;
use FSi\Component\DataGrid\DataGridEvent;
use FSi\Component\DataGrid\DataGridEvents;
use FSi\Component\DataGrid\Data\DataRowset;
use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\DataGridException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DataGrid implements DataGridInterface
{
    /**
     * Unique data grid name. With this name grid is registered in factory.
     *
     * @var string
     */
    protected $name;

    /**
     * DataCollection used to render view.
     *
     * @var \FSi\Component\DataGrid\Data\DataRowset
     */
    protected $rowset;

    /**
     * DataMapper used by all columns to retrieve data from rowset objects.
     *
     * @var \FSi\Component\DataGrid\DataMapper\DataMapperInterface
     */
    protected $dataMapper;

    /**
     * Factory that holds all column types and column types extensions.
     *
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $dataGridFactory;

    /**
     * Columns cloned from $dataGridFactory and used to render rowset view.
     *
     * @var array
     */
    protected $columns = array();

    /**
     * Symfony EventDispatcher mechanism that allow users to register listeners and subscribers.
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Indexing strategy used to index rowset original data under unique indexes.
     *
     * @var \FSi\Component\DataGrid\Data\IndexingStrategyInterface
     * @deprecated this field is deprecated and it will be removed in version 1.2
     */
    protected $strategy;

    /**
     * Constructs new DataGrid instance. Should be called only from DataGridFactory.
     *
     * @param string $name
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $dataGridFactory
     * @param \FSi\Component\DataGrid\DataMapper\DataMapperInterface $dataMapper
     * @param \FSi\Component\DataGrid\Data\IndexingStrategyInterface $strategy - deprecated and will be removed in 1.2
     */
    public function __construct($name, DataGridFactoryInterface $dataGridFactory, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy = null)
    {
        $this->name = $name;
        $this->dataGridFactory = $dataGridFactory;
        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->eventDispatcher = new EventDispatcher();
        $this->registerSubscribers();
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
    public function addColumn($name, $type = 'text', $options = array())
    {
        if ($name instanceof ColumnTypeInterface) {
            $type = $name->getId();

            if (!$this->dataGridFactory->hasColumnType($type)) {
                throw new UnexpectedTypeException(sprintf('There is no column with type "%s" registred in factory.', $type));
            }

            $name->setDataGrid($this);
            $this->columns[$name->getName()] = $name;
            return $this;
        }

        $column = $this->dataGridFactory->getColumnType($type);
        $column->setName($name)
            ->setDataGrid($this);

        $column->initOptions();
        foreach ($column->getExtensions() as $extension) {
            $extension->initOptions($column);
        }

        $column->setOptions($options);

        $this->columns[$name] = $column;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
        }

        return $this->columns[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumn($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($type)
    {
        foreach ($this->columns as $column) {
            if ($column->getId() == $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
        }

        unset($this->columns[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearColumns()
    {
        $this->columns = array();
        return $this;
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
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_SET_DATA, $event);
        $data = $event->getData();

        if (!is_array($data)) {
            if (!$data instanceof \Traversable) {
                throw new \InvalidArgumentException('Array or Traversable object is expected in setData method.');
            }
        }

        $this->rowset = new DataRowset($data);

        $event = new DataGridEvent($this, $this->rowset);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_SET_DATA, $event);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function bindData($data)
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_BIND_DATA, $event);
        $data = $event->getData();

        if (!is_array($data)) {
            if (!$data instanceof \ArrayIterator) {
                throw new \InvalidArgumentException('array or Traversable object is expected as data in bindData method.');
            }
        }

        foreach ($data as $index => $values) {
            if (!isset($this->rowset[$index])) {
                continue;
            }

            $object = $this->rowset[$index];

            foreach ($this->getColumns() as $column) {
                $column->bindData($values, $object, $index);
            }
        }

        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_BIND_DATA, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        $event = new DataGridEvent($this, null);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_BUILD_VIEW, $event);

        $view = new DataGridView($this->name, $this->columns, $this->getRowset());

        $event = new DataGridEvent($this, $view);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_BUILD_VIEW, $event);
        $view = $event->getData();

        return $view;
    }

    /**
     * Returns data grid rowset that contains source data.
     *
     * @throws \FSi\Component\DataGrid\Exception\DataGridException thrown when getRowset is called before setData
     * @return \FSi\Component\DataGrid\Data\DataRowset
     */
    private function getRowset()
    {
        if (!isset($this->rowset)) {
            throw new DataGridException(
                'Before you will be able to crete view from DataGrid you need to call method setData'
            );
        }

        return $this->rowset;
    }

    /**
     * Register all event subscribers provided by extensions.
     */
    private function registerSubscribers()
    {
        $extensions = $this->dataGridFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->registerSubscribers($this);
        }
    }
}
