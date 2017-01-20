<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource;

use FSi\Component\DataSource\Driver\DriverInterface;
use FSi\Component\DataSource\Exception\DataSourceException;
use FSi\Component\DataSource\Field\FieldTypeInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FSi\Component\DataSource\Event\DataSourceEvents;
use FSi\Component\DataSource\Event\DataSourceEvent;

/**
 * {@inheritdoc}
 */
class DataSource implements DataSourceInterface
{
    /**
     * Driver.
     *
     * @var \FSi\Component\DataSource\Driver\DriverInterface
     */
    private $driver;

    /**
     * Name of data source.
     *
     * @var string
     */
    private $name;

    /**
     * Fields of data source.
     *
     * @var array
     */
    private $fields = array();

    /**
     * Extensions of DataSource.
     *
     * @var array
     */
    private $extensions = array();

    /**
     * @var \FSi\Component\DataSource\DataSourceView
     */
    private $view;

    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    private $factory;

    /**
     * Max results fetched at once.
     *
     * @var int
     */
    private $maxResults;

    /**
     * Offset for first result.
     *
     * @var int
     */
    private $firstResult;

    /**
     * Cache for methods that depends on fields data (cache is dropped whenever any of fields is dirty, or fields have changed).
     *
     * @var array
     */
    private $cache = array();

    /**
     * Flag set as true when fields or their data is modifying, or even new extension is added.
     *
     * @var bool
     */
    private $dirty = true;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param \FSi\Component\DataSource\Driver\DriverInterface $driver
     * @param string $name
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function __construct(DriverInterface $driver, $name = 'datasource')
    {
        $name = (string) $name;

        if (empty($name)) {
            throw new DataSourceException('Name of data source can\t be empty.');
        }

        if (!preg_match('/^[\w\d]+$/', $name)) {
            throw new DataSourceException('Name of data source may contain only word characters and digits.');
        }

        $this->driver = $driver;
        $this->name = $name;
        $this->eventDispatcher = new EventDispatcher();
        $driver->setDataSource($this);
    }

    /**
     * {@inheritdoc}
     */
    public function hasField($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function addField($name, $type = null, $comparison = null, $options = array())
    {
        if ($name instanceof FieldTypeInterface) {
            $field = $name;
            $name = $name->getName();

            if (empty($name)) {
                throw new DataSourceException('Given field has no name set.');
            }
        } else {
            if (empty($type)) {
                throw new DataSourceException('"type" can\'t be null.');
            }
            if (empty($comparison)) {
                throw new DataSourceException('"comparison" can\'t be null.');
            }
            $field = $this->driver->getFieldType($type);
            $field->setName($name);
            $field->setComparison($comparison);
            $field->setOptions($options);
        }

        $this->dirty = true;
        $this->fields[$name] = $field;
        $field->setDataSource($this);

        return $this;
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
    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);
            $this->dirty = true;
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getField($name)
    {
        if (!$this->hasField($name)) {
            throw new DataSourceException(sprintf('There\'s no field with name "%s"', $name));
        }

        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function clearFields()
    {
        $this->fields = array();
        $this->dirty = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function bindParameters($parameters = array())
    {
        $this->dirty = true;

        //PreBindParameters event.
        $event = new DataSourceEvent\ParametersEventArgs($this, $parameters);
        $this->eventDispatcher->dispatch(DataSourceEvents::PRE_BIND_PARAMETERS, $event);
        $parameters = $event->getParameters();

        if (!is_array($parameters)) {
            throw new DataSourceException('Given parameters must be an array.');
        }

        foreach ($this->getFields() as $field) {
            $field->bindParameter($parameters);
        }

        //PostBindParameters event.
        $event = new DataSourceEvent\DataSourceEventArgs($this);
        $this->eventDispatcher->dispatch(DataSourceEvents::POST_BIND_PARAMETERS, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        $this->checkFieldsClarity();

        if (
            isset($this->cache['result'])
            && $this->cache['result']['maxresults'] == $this->getMaxResults()
            && $this->cache['result']['firstresult'] == $this->getFirstResult()
        ) {
            return $this->cache['result']['result'];
        }

        //PreGetResult event.
        $event = new DataSourceEvent\DataSourceEventArgs($this);
        $this->eventDispatcher->dispatch(DataSourceEvents::PRE_GET_RESULT, $event);

        $result = $this->driver->getResult($this->fields, $this->getFirstResult(), $this->getMaxResults());

        foreach ($this->getFields() as $field) {
            $field->setDirty(false);
        }

        if (!is_object($result)) {
            throw new DataSourceException('Returned result must be object implementing both Conutable and IteratorAggregate.');
        }

        if ((!$result instanceof \IteratorAggregate) || (!$result instanceof \Countable)) {
            throw new DataSourceException(sprintf('Returned result must be both Countable and IteratorAggregate, instance of "%s" given.', get_class($result)));
        }

        //PostGetResult event.
        $event = new DataSourceEvent\ResultEventArgs($this, $result);
        $this->eventDispatcher->dispatch(DataSourceEvents::POST_GET_RESULT, $event);
        $result = $event->getResult();

        //Creating cache.
        $this->cache['result'] = array(
            'result' => $result,
            'firstresult' => $this->getFirstResult(),
            'maxresults' => $this->getMaxResults(),
        );

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxResults($max)
    {
        $this->dirty = true;
        $this->maxResults = $max;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstResult($first)
    {
        $this->dirty = true;
        $this->firstResult = $first;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstResult()
    {
        return $this->firstResult;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(DataSourceExtensionInterface $extension)
    {
        $this->dirty = true;
        $this->extensions[] = $extension;

        foreach ((array) $extension->loadSubscribers() as $subscriber) {
            $this->eventDispatcher->addSubscriber($subscriber);
        }

        foreach ((array) $extension->loadDriverExtensions() as $driverExtension) {
            if (in_array($this->driver->getType(), $driverExtension->getExtendedDriverTypes())) {
                $this->driver->addExtension($driverExtension);
            }
        }
        return $this;
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
    public function createView()
    {
        $view = new DataSourceView($this);

        //PreBuildView event.
        $event = new DataSourceEvent\ViewEventArgs($this, $view);
        $this->eventDispatcher->dispatch(DataSourceEvents::PRE_BUILD_VIEW, $event);

        foreach ($this->fields as $key => $field) {
            $view->addField($field->createView());
        }

        $this->view = $view;

        //PostBuildView event.
        $event = new DataSourceEvent\ViewEventArgs($this, $view);
        $this->eventDispatcher->dispatch(DataSourceEvents::POST_BUILD_VIEW, $event);

        return $this->view;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        $this->checkFieldsClarity();
        if (isset($this->cache['parameters'])) {
            return $this->cache['parameters'];
        }

        $parameters = array();

        //PreGetParameters event.
        $event = new DataSourceEvent\ParametersEventArgs($this, $parameters);
        $this->eventDispatcher->dispatch(DataSourceEvents::PRE_GET_PARAMETERS, $event);
        $parameters = $event->getParameters();

        foreach ($this->fields as $field) {
            $field->getParameter($parameters);
        }

        //PostGetParameters event.
        $event = new DataSourceEvent\ParametersEventArgs($this, $parameters);
        $this->eventDispatcher->dispatch(DataSourceEvents::POST_GET_PARAMETERS, $event);
        $parameters = $event->getParameters();

        $cleanfunc = function(array $array) use (&$cleanfunc) {
            $newArray = array();
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $newValue = $cleanfunc($value);
                    if (!empty($newValue)) {
                        $newArray[$key] = $newValue;
                    }
                } elseif (is_scalar($value) && (!empty($value) || is_numeric($value))) {
                    $newArray[$key] = $value;
                }
            }
            return $newArray;
        };

        //Clearing parameters from empty values.
        $parameters = $cleanfunc($parameters);

        $this->cache['parameters'] = $parameters;
        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllParameters()
    {
        if ($this->factory) {
            return $this->factory->getAllParameters();
        }
        return $this->getParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getOtherParameters()
    {
        if ($this->factory) {
            return $this->factory->getOtherParameters($this);
        }
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function setFactory(DataSourceFactoryInterface $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Checks if from last time some of data has changed, and if did, resets cache.
     */
    private function checkFieldsClarity()
    {
        //Initialize with dirty flag.
        $dirty = $this->dirty;
        foreach ($this->getFields() as $field) {
            $dirty = $dirty || $field->isDirty();
        }

        //If flag was set to dirty, or any of fields was dirty, reset cache.
        if ($dirty) {
            $this->cache = array();
            $this->dirty = false;
        }
    }
}
