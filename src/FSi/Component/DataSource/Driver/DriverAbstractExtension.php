<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Exception\DataSourceException;
use FSi\Component\DataSource\Field\FieldTypeInterface;
use FSi\Component\DataSource\Field\FieldExtensionInterface;

/**
 * {@inheritdoc}
 */
abstract class DriverAbstractExtension implements DriverExtensionInterface, EventSubscriberInterface
{
    /**
     * Array of fields types.
     *
     * @var array
     */
    private $fieldTypes;

    /**
     * Array of fields extensions.
     *
     * @var array
     */
    private $fieldTypesExtensions;

    /**
     * {@inheritdoc}
     */
    public function hasFieldType($type)
    {
        if (!isset($this->fieldTypes)) {
            $this->initFieldsTypes();
        }

        return isset($this->fieldTypes[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType($type)
    {
        if (!isset($this->fieldTypes)) {
            $this->initFieldsTypes();
        }

        if (!isset($this->fieldTypes[$type])) {
            throw new DataSourceException(sprintf('Field with type "%s" can\'t be loaded.', $type));
        }

        return $this->fieldTypes[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldTypeExtensions($type)
    {
        if (!isset($this->fieldTypesExtensions)) {
            $this->initFieldTypesExtensions();
        }

        return isset($this->fieldTypesExtensions[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldTypeExtensions($type)
    {
        if (!isset($this->fieldTypesExtensions)) {
            $this->initFieldTypesExtensions();
        }

        if (!isset($this->fieldTypesExtensions[$type])) {
            throw new DataSourceException(sprintf('Field extensions with type "%s" can\'t be loaded.', $type));
        }

        return $this->fieldTypesExtensions[$type];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes()
    {
        return array();
    }

    /**
     * Initializes every field type in extension.
     *
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    private function initFieldsTypes()
    {
        $this->fieldTypes = array();

        $fieldTypes = $this->loadFieldTypes();

        foreach ($fieldTypes as $fieldType) {
            if (!$fieldType instanceof FieldTypeInterface) {
                throw new DataSourceException(sprintf('Expected instance of FieldTypeInterface, "%s" given.', get_class($fieldType)));
            }

            if (isset($this->fieldTypes[$fieldType->getType()])) {
                throw new DataSourceException(sprintf('Error during field types loading. Name "%s" already in use.', $fieldType->getType()));
            }

            $this->fieldTypes[$fieldType->getType()] = $fieldType;
        }
    }

    /**
     * Initializes every field extension if extension.
     *
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    private function initFieldTypesExtensions()
    {
        $fieldTypesExtensions = $this->loadFieldTypesExtensions();
        foreach ($fieldTypesExtensions as $extension) {
            if (!$extension instanceof FieldExtensionInterface) {
                throw new DataSourceException(sprintf("Expected instance of FSi\Component\DataSource\Field\FieldExtensionInterface but %s got", get_class($extension)));
            }

            $types = $extension->getExtendedFieldTypes();
            foreach ($types as $type) {
                if (!isset($this->fieldTypesExtensions)) {
                    $this->fieldTypesExtensions[$type] = array();
                }
                $this->fieldTypesExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return array($this);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array();
    }
}
