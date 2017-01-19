<?php


namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormEvent extends AdminEvent
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $form
     */
    public function __construct(Element $element, Request $request, FormInterface $form)
    {
        parent::__construct($element, $request);
        $this->form = $form;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
