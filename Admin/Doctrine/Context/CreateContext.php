<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CreateContext implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement
     */
    protected $element;

    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @param $requestHandlers
     */
    public function __construct($requestHandlers)
    {
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * @param CRUDElement $element
     */
    public function setElement(CRUDElement $element)
    {
        $this->element = $element;
        $this->form = $this->element->createForm();
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

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template_crud_create');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_crud_create');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return array(
            'element' => $this->element,
            'form' => $this->form->createView(),
            'title' => $this->element->getOption('crud_create_title'),
        );
    }
}
