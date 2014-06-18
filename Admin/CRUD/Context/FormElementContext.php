<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormElementContext implements ContextInterface
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
     * @var FormInterface
     */
    protected $form;

    /**
     * @param array $requestHandlers
     */
    public function __construct($requestHandlers)
    {
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * @param ListElement $element
     */
    public function setElement(FormElement $element, $formData = null)
    {
        $this->element = $element;
        $this->form = $this->element->createForm($formData);
    }

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
    public function handleRequest(Request $request)
    {
        $event = new FormEvent($this->element, $request, $this->form);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }
}
