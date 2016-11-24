<?php

namespace FSi\Bundle\AdminBundle\Form;

use Symfony\Component\Form\FormTypeInterface;

/**
 * @internal
 */
class FeatureHelper
{
    /**
     * Return FQCN form type or old style form type
     *
     * @param string $fqcnType
     * @param string|FormTypeInterface $shortType
     * @return string|FormTypeInterface
     */
    public static function getFormType($fqcnType, $shortType)
    {
        return class_exists('Symfony\Component\Form\Extension\Core\Type\RangeType') ? $fqcnType : $shortType;
    }

    /**
     * @return bool
     */
    public static function hasCollectionEntryTypeOption()
    {
        return class_exists('Symfony\Component\Form\Extension\Core\Type\RangeType');
    }

    /**
     * @return bool
     */
    public static function isChoicesAsValuesOptionTrueByDefault()
    {
        return method_exists('Symfony\Component\Form\FormTypeInterface', 'configureOptions');
    }
}
