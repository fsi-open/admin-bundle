<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Field;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extension of DataSources field.
 */
interface FieldExtensionInterface extends EventSubscriberInterface
{
    /**
     * Returns array of extended types.
     *
     * @return array
     */
    public function getExtendedFieldTypes();

    /**
     * Allows extension to load options' constraints to fields OptionsResolver. Called by field.
     *
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function initOptions(FieldTypeInterface $field);
}
