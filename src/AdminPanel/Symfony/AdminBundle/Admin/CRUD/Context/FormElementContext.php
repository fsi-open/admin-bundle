<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextAbstract;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormElementContext extends ContextAbstract
{
    /**
     * @var FormElement
     */
    protected $element;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template_form');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_form');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [
            'form' => $this->form->createView(),
            'element' => $this->element
        ];

        if ($this->form->getData()) {
            $data['id'] = $this->element->getDataIndexer()->getIndex($this->form->getData());
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent(Request $request)
    {
        $this->form = $this->element->createForm($this->getObject($request));

        return new FormEvent($this->element, $request, $this->form);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof FormElement;
    }

    /**
     * @param Request $request
     * @return object|null
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            return null;
        }

        $object = $this->element->getDataIndexer()->getData($id);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('Can\'t find object with id %s', $id));
        }

        return $object;
    }
}
