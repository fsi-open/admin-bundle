<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form\Extension;

use FSi\Bundle\AdminBundle\Translatable\Form\TranslatablePropertyBuilder;
use FSi\Bundle\AdminBundle\Translatable\Form\TranslatableFormDataExtractor;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\CKEditorType;
use FSi\Component\Files\Integration\Symfony\Form\WebFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class TranslatableExtension extends AbstractTypeExtension
{
    private TranslatableFormDataExtractor $formDataExtractor;
    private TranslatablePropertyBuilder $propertyBuilder;

    public function __construct(
        TranslatableFormDataExtractor $formDataExtractor,
        TranslatablePropertyBuilder $propertyBuilder
    ) {
        $this->formDataExtractor = $formDataExtractor;
        $this->propertyBuilder = $propertyBuilder;
    }

    /**
     * @return iterable<int, class-string<FormTypeInterface>>
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            CKEditorType::class,
            ChoiceType::class,
            CollectionType::class,
            EntityType::class,
            TextType::class,
            TextareaType::class,
            WebFileType::class
        ];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $propertyData = $this->propertyBuilder->buildIfTranslatable(
            $this->formDataExtractor->extract($form),
            (string) $form->getPropertyPath()
        );

        if (false === $propertyData->isTranslatable()) {
            return;
        }

        $view->vars['default_translation'] = $propertyData->getDefaultTranslation();
        $view->vars['translatable'] = true;
    }
}
