<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\DataGrid\Extension;

use FSi\Bundle\DataGridBundle\DataGrid\ColumnType\Files\File;
use FSi\Bundle\DataGridBundle\DataGrid\ColumnType\Files\Image;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\ColumnType\Text;
use FSi\Component\DataGrid\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use FSi\Component\DataGrid\ColumnTypeExtension\ValueFormatter;
use FSi\Component\Translatable\ConfigurationResolver;
use FSi\Component\Translatable\TranslatableConfiguration;
use FSi\Component\Translatable\TranslationConfiguration;
use FSi\Component\Translatable\TranslationProvider;
use RuntimeException;

use function count;
use function is_array;
use function is_object;
use function is_string;
use function reset;

final class DefaultLocaleExtension extends ValueFormatColumnOptionsExtension
{
    protected ValueFormatter $valueFormatter;

    private ConfigurationResolver $configurationResolver;
    private TranslationProvider $translationProvider;
    private string $defaultLocale;

    public static function getExtendedColumnTypes(): array
    {
        return [File::class, Image::class, Text::class];
    }

    public function __construct(
        ConfigurationResolver $configurationResolver,
        TranslationProvider $translationProvider,
        ValueFormatter $valueFormatter,
        string $defaultLocale
    ) {
        parent::__construct($valueFormatter);
        $this->configurationResolver = $configurationResolver;
        $this->translationProvider = $translationProvider;
        $this->valueFormatter = $valueFormatter;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param string|int $index
     * @param mixed $source
     */
    public function buildCellView(ColumnInterface $column, CellViewInterface $view, $index, $source): void
    {
        if (false === is_object($source)) {
            return;
        }

        if (false === $this->configurationResolver->isTranslatable($source)) {
            return;
        }

        $configuration = $this->configurationResolver->resolveTranslatable($source);
        $defaultTranslation = $this->getDefaultTranslation($configuration, $source);
        if (null === $defaultTranslation) {
            return;
        }

        /** @var list<string> $fieldMapping */
        $fieldMapping = $column->getOption('field_mapping');
        if (0 === count($fieldMapping)) {
            throw new RuntimeException(
                "Unable to read field mapping from column \"{$column->getName()}\""
            );
        }

        $currentValues = $view->getValue();
        $currentValuesTranslations = $this->extractCurrentValuesTranslationInformation(
            $fieldMapping,
            $configuration,
            $this->normalizeCurrentValues($currentValues, $fieldMapping)
        );

        $defaultValues = $this->createDefaultValues(
            $configuration->getTranslationConfiguration(),
            $defaultTranslation,
            $currentValuesTranslations
        );

        if (0 === count($defaultValues)) {
            return;
        }

        $view->setValue($this->formatValue($column, $defaultValues));
        $view->setAttribute('default_translation', true);
    }

    /**
     * @param array<
     *  array{
     *      field: string,
     *      translatable: bool,
     *      empty: bool,
     *      currentValue: mixed
     *  }
     * > $currentValuesTranslations
     * @return array<string, mixed>
     */
    private function createDefaultValues(
        TranslationConfiguration $configuration,
        object $defaultTranslation,
        array $currentValuesTranslations
    ) {
        $nonEmptyTranslatableFields = array_filter(
            $currentValuesTranslations,
            static fn(array $information): bool
                => true === $information['translatable'] && false === $information['empty']
        );

        if (0 !== count($nonEmptyTranslatableFields)) {
            return [];
        }

        return array_reduce(
            $currentValuesTranslations,
            function (array $accumulator, array $information) use ($defaultTranslation, $configuration): array {
                $field = $information['field'];
                if (true === $information['translatable']) {
                    $defaultValue = $configuration->getValueForProperty($defaultTranslation, $field);
                } else {
                    $defaultValue = $information['currentValue'];
                }

                if (false === $this->isValueEmpty($defaultValue)) {
                    $accumulator[$field] = $defaultValue;
                }

                return $accumulator;
            },
            []
        );
    }

    private function getDefaultTranslation(
        TranslatableConfiguration $configuration,
        object $source
    ): ?object {
        if ($configuration->getLocale($source) === $this->defaultLocale) {
            return null;
        }

        return $this->translationProvider->findForEntityAndLocale(
            $source,
            $this->defaultLocale
        );
    }

    /**
     * @param list<string> $fieldMapping
     * @param array<string, mixed> $currentValues
     * @return array<array{ field: string, translatable: bool, empty: bool, currentValue: mixed }>
     */
    private function extractCurrentValuesTranslationInformation(
        array $fieldMapping,
        TranslatableConfiguration $configuration,
        array $currentValues
    ): array {
        $result = [];
        foreach ($fieldMapping as $field) {
            $value = $currentValues[$field] ?? null;
            $result[] = [
                'field' => $field,
                'translatable' => $configuration->isPropertyTranslatable($field),
                'empty' => $this->isValueEmpty($value),
                'currentValue' => $value
            ];
        }

        return $result;
    }

    /**
     * @param mixed $currentValues
     * @param list<string> $fieldMapping
     * @return array<string, mixed>
     */
    private function normalizeCurrentValues($currentValues, array $fieldMapping): array
    {
        if (true === is_array($currentValues) && 1 < count($currentValues)) {
            return $currentValues;
        }

        $normalizedValue = true === is_array($currentValues)
            ? reset($currentValues)
            : $currentValues
        ;

        /** @var string $field */
        $field = reset($fieldMapping);
        return [$field => $normalizedValue];
    }

    /**
     * @param mixed $defaultValues
     * @return mixed
     */
    private function formatValue(ColumnInterface $column, $defaultValues)
    {
        /** @var string|null $glue */
        $glue = $column->getOption('value_glue');
        /** @var string|callable|null $format */
        $format = $column->getOption('value_format');
        /** @var mixed $emptyValue */
        $emptyValue = $column->getOption('empty_value');

        return $this->valueFormatter->format($defaultValues, $glue, $format, $emptyValue);
    }

    /**
     * @param mixed $value
     */
    private function isValueEmpty($value): bool
    {
        return true === is_string($value) ? '' === trim($value) : null === $value;
    }
}
