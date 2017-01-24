<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Field;

/**
 * {@inheritdoc}
 */
class FieldAbstractExtension implements FieldExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
    }
}
