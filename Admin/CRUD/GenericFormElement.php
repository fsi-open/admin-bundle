<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Component\Translatable\LocaleProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_merge;

/**
 * @template T of array<string,mixed>|object
 * @template TSaveDTO of array<string,mixed>|object
 * @template-default TSaveDTO=T
 * @template-implements FormElement<T, TSaveDTO>
 */
abstract class GenericFormElement extends AbstractElement implements FormElement
{
    protected FormFactoryInterface $formFactory;

    private LocaleProvider $localeProvider;

    public function getRoute(): string
    {
        return 'fsi_admin_form';
    }

    public function getRouteParameters(): array
    {
        return array_merge(parent::getRouteParameters(), ['translatableLocale' => $this->localeProvider->getLocale()]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template_form' => null,
            'allow_add' => true
        ]);

        $resolver->setAllowedTypes('template_form', ['null', 'string']);
        $resolver->setAllowedTypes('allow_add', 'bool');
    }

    public function setLocaleProvider(LocaleProvider $localeProvider): void
    {
        $this->localeProvider = $localeProvider;
    }

    public function setFormFactory(FormFactoryInterface $factory): void
    {
        $this->formFactory = $factory;
    }

    public function createForm($data = null): FormInterface
    {
        return $this->initForm($this->formFactory, $data);
    }

    public function getSuccessRoute(): string
    {
        return $this->getRoute();
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->getRouteParameters();
    }

    /**
     * Initialize Form. This form will be used in create and update actions.
     *
     * @param FormFactoryInterface $factory
     * @param T $data
     * @return FormInterface<string,FormInterface>
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface;
}
