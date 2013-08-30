<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class EditContext implements ContextInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement
     */
    protected $element;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param EventDispatcher $dispatcher
     * @param \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement $element
     * @param \Symfony\Component\Routing\Router $router
     * @param $data mixed
     */
    public function __construct(EventDispatcher $dispatcher, CRUDElement $element, Router $router, $data)
    {
        $this->dispatcher = $dispatcher;
        $this->element = $element;
        $this->router = $router;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new AdminEvent($this->element, $request);

        $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->isMethod('POST')) {
            $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_FORM_REQUEST_PRE_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->element->getEditForm($this->data)->submit($request);

            $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_FORM_REQUEST_POST_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            if ($this->element->getEditForm($this->data)->isValid()) {
                $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_ENTITY_PRE_SAVE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }
            }

            $this->element->save($this->element->getEditForm($this->data)->getData());

            $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_ENTITY_POST_SAVE, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            return new RedirectResponse($this->router->generate('fsi_admin_crud_list', array(
                'element' => $this->element->getId(),
            )));
        }


        $this->dispatcher->dispatch(AdminEvents::CRUD_EDIT_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
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
            'form' => $this->element->getEditForm($this->data)->createView(),
            'id' => $this->element->getDataIndexer()->getIndex($this->data)
        );
    }
}