<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Field;

use FSi\Component\DataSource\DataSourceInterface;

/**
 * Field of DataSource.
 */
interface FieldTypeInterface
{
    /**
     * Returns type of given field.
     *
     * @return string
     */
    public function getType();

    /**
     * Sets name for field.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Returns name of field.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets comparison for field.
     *
     * @param string $comparison
     */
    public function setComparison($comparison);

    /**
     * Returns comparison.
     *
     * @return string
     */
    public function getComparison();

    /**
     * Return array of available comparisons.
     *
     * @return array
     */
    public function getAvailableComparisons();

    /**
     * Sets options for field. All previously set options will be overwritten.
     *
     * @param array $options
     */
    public function setOptions($options);

    /**
     * Checks whether field has option with given name.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * Return option with given name.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Returns previously set options (or empty array otherwise).
     *
     * @return array
     */
    public function getOptions();

    /**
     * Sets parameter for field.
     *
     * @param mixed $parameter
     */
    public function bindParameter($data);

    /**
     * Assigns parameter to proper place.
     *
     * @apram array &$parameters
     */
    public function getParameter(&$parameters);

    /**
     * Returns parameter.
     *
     * @return mixed
     */
    public function getCleanParameter();

    /**
     * Adds extension to field.
     *
     * @param \FSi\Component\DataSource\Field\FieldExtensionInterface $extension
     */
    public function addExtension(FieldExtensionInterface $extension);

    /**
     * Replace field extensions with specified ones.
     *
     * @param array $extensions
     */
    public function setExtensions(array $extensions);

    /**
     * Returns array of registered extensions.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Builds view.
     *
     * @return \FSi\Component\DataSource\Field\FieldViewInterface
     */
    public function createView();

    /**
     * Checks if data of field has changed in any way since last time, when field was set as clean.
     *
     * @return bool
     */
    public function isDirty();

    /**
     * Sets dirty flag.
     *
     * @param bool $dirty
     */
    public function setDirty($dirty = true);

    /**
     * Sets reference to datasource.
     *
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     */
    public function setDataSource(DataSourceInterface $datasource);

    /**
     * Returns datasource.
     *
     * @return \FSi\Component\DataSource\DataSourceInterface|null
     */
    public function getDataSource();

    /**
     * Sets the default options for this type.
     *
     * In order to access OptionsResolver in this method use $this->getOptionsResolver()
     * in inherited classes. This method is called in DataSource after loading the field type
     * from factory.
     */
    public function initOptions();

    /**
     * Returns the configured options resolver used for this field's type.
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function getOptionsResolver();
}
