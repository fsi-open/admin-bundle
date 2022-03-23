<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use Symfony\Component\Form\FormFactoryInterface;

class FormWorker implements Worker
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function mount(Element $element): void
    {
        if (true === $element instanceof FormElement) {
            $element->setFormFactory($this->formFactory);
        }
    }
}
