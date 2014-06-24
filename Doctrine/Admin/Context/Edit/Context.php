<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Context implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

    /**
     * @var CRUDElement
     */
    protected $element;

    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @param array $requestHandlers
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
    }

    /**
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->form = $this->element->createForm($entity);
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
        return $this->element->hasOption('template_crud_edit');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_crud_edit');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return array(
            'element' => $this->element,
            'form' => $this->form->createView(),
            'id' => $this->element->getDataIndexer()->getIndex($this->entity)
        );
    }
}
