<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
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
    function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(ElementInterface $element)
    {
        if ($element instanceof FormAwareInterface) {
            $element->setFormFactory($this->formFactory);
        }
    }
}
