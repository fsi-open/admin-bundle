<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BatchElementContext implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

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
     * @param array $requestHandlers
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     */
    public function __construct(
        $requestHandlers,
        FormBuilderInterface $formBuilder
    ) {
        $this->requestHandlers = $requestHandlers;
        $this->form = $formBuilder->getForm();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     */
    public function setElement(BatchElement $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return null;
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
    public function handleRequest(Request $request)
    {
        $event = new FormEvent($this->element, $request, $this->form);
        $this->indexes = $request->request->get('indexes', array());

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }
}
