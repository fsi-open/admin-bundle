<?php

namespace AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;
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
    function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof FormAwareInterface || $element instanceof FormElement) {
            $element->setFormFactory($this->formFactory);
        }
    }
}
