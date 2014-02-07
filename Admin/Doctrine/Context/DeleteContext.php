<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class DeleteContext implements ContextInterface
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
     * @var array
     */
    protected $indexes;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param array $requestHandlers
     */
    public function __construct(
        FormFactoryInterface $factory,
        array $requestHandlers
    ) {
        $this->factory = $factory;
        $this->requestHandlers = $requestHandlers;
        $this->form = $this->factory->createNamed('delete', 'form');
    }

    /**
     * @param CRUDElement $element
     */
    public function setElement(CRUDElement $element)
    {
        $this->element = $element;
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
/*
        $this->dispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->request->has('confirm')) {
            $this->dispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->form->submit($request);

            $this->dispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_POST_SUBMIT, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            if ($this->form->isValid()) {
                $this->dispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                foreach ($this->data as $entity) {
                    $this->element->delete($entity);
                }

                $this->dispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }
            }

            return new RedirectResponse($this->router->generate('fsi_admin_crud_list', array('element' => $this->element->getId())));
        }

        if ($request->request->has('cancel')) {
            return new RedirectResponse($this->router->generate('fsi_admin_crud_list', array('element' => $this->element->getId())));
        }

        return null;*/
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template_crud_delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_crud_delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return array(
            'element' => $this->element,
            'indexes' => $this->indexes,
            'form' => $this->form->createView(),
        );
    }
}
