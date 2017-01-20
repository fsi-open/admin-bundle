<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Field;

use FSi\Component\DataSource\Exception\FieldException;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FSi\Component\DataSource\Event\FieldEvents;
use FSi\Component\DataSource\Event\FieldEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * {@inheritdoc}
 */
abstract class FieldAbstractType implements FieldTypeInterface
{
    /**
     * Array of allowed comparisons.
     *
     * @var array
     */
    protected $comparisons = array();

    /**
     * Name of element.
     *
     * @var string
     */
    protected $name;

    /**
     * Set comparison.
     *
     * @var string
     */
    protected $comparison;

    /**
     * Given options.
     *
     * @var array
     */
    private $options = array();

    /**
     * Given parameter.
     *
     * @var mixed
     */
    protected $parameter;

    /**
     * Flag to determine if inner state has changed.
     *
     * @var bool
     */
    protected $dirty = true;

    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    protected $datasource;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var array
     */
    private $extensions = array();

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    public function __clone()
    {
        $this->eventDispatcher = null;
        $this->optionsResolver = null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \FSi\Component\DataSource\Exception\FieldException
     */
    public function setComparison($comparison)
    {
        if (!in_array($comparison, $this->getAvailableComparisons())) {
            throw new FieldException(sprintf('Comparison "%s" not allowed for this type of field ("%s").', $comparison, $this->getType()));
        }

        $this->comparison = $comparison;
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
    public function getAvailableComparisons()
    {
        return $this->comparisons;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->options = $this->getOptionsResolver()->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \FSi\Component\DataSource\Exception\FieldException
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new FieldException(sprintf('There\'s no option named "%s"', is_scalar($name) ? $name : gettype($name)));
        }
        return $this->options[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function bindParameter($parameter)
    {
        $this->setDirty();

        //PreBindParameter event.
        $event = new FieldEvent\ParameterEventArgs($this, $parameter);
        $this->getEventDispatcher()->dispatch(FieldEvents::PRE_BIND_PARAMETER, $event);
        $parameter = $event->getParameter();

        $datasourceName = $this->getDataSource() ? $this->getDataSource()->getName() : null;
        if (!empty($datasourceName) && isset($parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$this->getName()])) {
            $parameter = $parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$this->getName()];
        } else {
            $parameter = null;
        }

        $this->parameter = $parameter;

        //PreBindParameter event.
        $event = new FieldEvent\FieldEventArgs($this);
        $this->getEventDispatcher()->dispatch(FieldEvents::POST_BIND_PARAMETER, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter(&$parameters)
    {
        $datasourceName = $this->getDataSource() ? $this->getDataSource()->getName() : null;
        if (!empty($datasourceName)) {
            $parameter = array(
                $datasourceName => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        $this->getName() => $this->getCleanParameter(),
                    ),
                ),
            );
        } else {
            $parameter = array();
        }

        //PostGetParameter event.
        $event = new FieldEvent\ParameterEventArgs($this, $parameter);
        $this->getEventDispatcher()->dispatch(FieldEvents::POST_GET_PARAMETER, $event);
        $parameter = $event->getParameter();

        $parameters = array_merge_recursive($parameters, $parameter);
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanParameter()
    {
        return $this->parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(FieldExtensionInterface $extension)
    {
        if (in_array($extension, $this->extensions, true)) {
            return;
        }

        $this->getEventDispatcher()->addSubscriber($extension);
        $extension->initOptions($this);
        $this->extensions[] = $extension;

        $this->options = $this->getOptionsResolver()->resolve($this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof FieldExtensionInterface) {
                throw new FieldException(sprintf('Expected instance of FieldExtensionInterface, %s given', get_class($extension)));
            }
            $this->getEventDispatcher()->addSubscriber($extension);
            $extension->initOptions($this);
        }
        $this->options = $this->getOptionsResolver()->resolve($this->options);
        $this->extensions = $extensions;
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
        $view = new FieldView($this);

        //PostBuildView event.
        $event = new FieldEvent\ViewEventArgs($this, $view);
        $this->getEventDispatcher()->dispatch(FieldEvents::POST_BUILD_VIEW, $event);

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirty()
    {
        return $this->dirty;
    }

    /**
     * {@inheritdoc}
     */
    public function setDirty($dirty = true)
    {
        $this->dirty = (bool) $dirty;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSource(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource()
    {
        return $this->datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver()
    {
        if (!isset($this->optionsResolver)) {
            $this->optionsResolver = new OptionsResolver();
        }

        return $this->optionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEventDispatcher()
    {
        if (!isset($this->eventDispatcher)) {
            $this->eventDispatcher = new EventDispatcher();
        }

        return $this->eventDispatcher;
    }
}
