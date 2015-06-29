<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormElementContext extends ContextAbstract
{
    /**
     * @var GenericFormElement
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
        $data = array(
            'form' => $this->form->createView(),
            'element' => $this->element
        );

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
        return $element instanceof GenericFormElement;
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
