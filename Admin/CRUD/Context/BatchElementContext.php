<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BatchElementContext extends ContextAbstract
{
    /**
     * @var FormElement
     */
    protected $element;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     */
    public function __construct(
        array $requestHandlers,
        FormBuilderInterface $formBuilder
    ) {
        parent::__construct($requestHandlers);

        $this->form = $formBuilder->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = array(
            'element' => $this->element,
            'indexes' => $this->indexes,
            'form' => $this->form->createView()
        );

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
        $this->indexes = $request->request->get('indexes', array());

        return new FormEvent($this->element, $request, $this->form);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_batch';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof BatchElement;
    }
}
