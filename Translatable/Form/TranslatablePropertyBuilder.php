<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form;

use FSi\Component\Files\WebFile;
use FSi\Component\Translatable\ConfigurationResolver;
use FSi\Component\Translatable\TranslationProvider;

use function is_string;

final class TranslatablePropertyBuilder
{
    private ConfigurationResolver $configurationResolver;
    private TranslationProvider $translationProvider;
    private string $defaultLocale;

    public function __construct(
        ConfigurationResolver $configurationResolver,
        TranslationProvider $translationProvider,
        string $defaultLocale
    ) {
        $this->configurationResolver = $configurationResolver;
        $this->translationProvider = $translationProvider;
        $this->defaultLocale = $defaultLocale;
    }

    public function buildIfTranslatable(FormTranslatableData $formData, string $property): PropertyTranslatableData
    {
        if (false === $formData->isTranslatable() || null === $formData->getData()) {
            return $this->createProperty(false, null);
        }

        $data = $formData->getData();
        $configuration = $this->configurationResolver->resolveTranslatable($data);

        if (false === $configuration->isPropertyTranslatable($property)) {
            return $this->createProperty(false, null);
        }

        if ($configuration->getLocale($data) === $this->defaultLocale) {
            return $this->createProperty(true, null);
        }

        $translation = $this->translationProvider->findForEntityAndLocale($data, $this->defaultLocale);
        if (null === $translation) {
            return $this->createProperty(true, null);
        }

        $propertyValue = $this
            ->configurationResolver
            ->resolveTranslation($translation)
            ->getValueForProperty($translation, $property)
        ;

        if (null === $propertyValue || '' === $propertyValue) {
            return $this->createProperty(true, null);
        }

        if (false === is_string($propertyValue) && false === $propertyValue instanceof WebFile) {
            return $this->createProperty(true, null);
        }

        return $this->createProperty(true, $propertyValue);
    }

    /**
     * @param WebFile|string|null $data
     */
    private function createProperty(bool $translatable, $data): PropertyTranslatableData
    {
        return new PropertyTranslatableData(
            $translatable,
            null !== $data ? new DefaultTranslation($data) : null
        );
    }
}
