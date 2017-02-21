<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use Symfony\Component\Form\FormFactoryInterface;

class FormWorker implements Worker
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof FormElement) {
            $element->setFormFactory($this->formFactory);
        }
    }
}
