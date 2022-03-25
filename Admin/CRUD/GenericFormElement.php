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
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericFormElement extends AbstractElement implements FormElement
{
    protected FormFactoryInterface $formFactory;

    public function getRoute(): string
    {
        return 'fsi_admin_form';
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
     * @param mixed $data
     * @return FormInterface<string,FormInterface>
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface;
}
