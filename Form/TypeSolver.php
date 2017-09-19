<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Form;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

/**
 * @internal
 */
class TypeSolver
{
    /**
     * Return FQCN form type or old style form type
     *
     * @param string $fqcnType
     * @param string|FormTypeInterface $shortType
     * @return string|FormTypeInterface
     */
    public static function getFormType(string $fqcnType, $shortType)
    {
        return class_exists(RangeType::class) ? $fqcnType : $shortType;
    }

    public static function hasCollectionEntryTypeOption(): bool
    {
        return class_exists(RangeType::class);
    }

    public static function isChoicesAsValuesOptionTrueByDefault(): bool
    {
        return method_exists(FormTypeInterface::class, 'configureOptions');
    }
}
