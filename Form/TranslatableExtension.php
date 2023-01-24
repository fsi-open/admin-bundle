<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Form;

use FSi\Component\Translatable\ConfigurationResolver;
use FSi\Component\Translatable\PropertyConfiguration;
use FSi\Component\Translatable\TranslatableConfiguration;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;

final class TranslatableExtension extends AbstractTypeExtension
{
    private ConfigurationResolver $configurationResolver;

    /**
     * @return iterable<class-string<FormTypeInterface>>
     */
    public static function getExtendedTypes(): iterable
    {
        return [TextType::class, CollectionType::class];
    }

    public function __construct(ConfigurationResolver $configurationResolver)
    {
        $this->configurationResolver = $configurationResolver;
    }

    /**
     * @param FormView<FormView> $view
     * @param FormInterface<FormInterface> $form
     * @param array<string, mixed> $options
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $translatableConfiguration = $this->extractFirstTranslatableParentConfiguration($form);
        if (null !== $translatableConfiguration) {
            $isTranslatable = $this->isPropertyPathTranslatable($form, $translatableConfiguration);
        } else {
            $isTranslatable = false;
        }

        $view->vars['translatable'] = $isTranslatable;
    }

    /**
     * @param FormInterface<FormInterface> $form
     * @return TranslatableConfiguration|null
     */
    private function extractFirstTranslatableParentConfiguration(
        FormInterface $form
    ): ?TranslatableConfiguration {
        $configuration = null;
        for ($parent = $form; $parent !== null; $parent = $parent->getParent()) {
            if (true === $parent->getConfig()->getInheritData()) {
                continue;
            }

            /** @var class-string<object>|null $dataClass */
            $dataClass = $parent->getConfig()->getDataClass();
            if (null === $dataClass) {
                continue;
            }

            if (true === $this->configurationResolver->isTranslatable($dataClass)) {
                $configuration = $this->configurationResolver->resolveTranslatable($dataClass);
                break;
            }
        }

        return $configuration;
    }

    /**
     * @param FormInterface<FormInterface> $form
     * @param TranslatableConfiguration $translatableConfiguration
     */
    private function isPropertyPathTranslatable(
        FormInterface $form,
        TranslatableConfiguration $translatableConfiguration
    ): bool {
        $propertyPath = null !== $form->getPropertyPath()
            ? (string) $form->getPropertyPath()
            : null
        ;

        if (null === $propertyPath) {
            return false;
        }

        return array_reduce(
            $translatableConfiguration->getPropertyConfigurations(),
            fn(bool $accumulator, PropertyConfiguration $configuration): bool
                => true === $accumulator || $configuration->getPropertyName() === $propertyPath,
            false
        );
    }
}
