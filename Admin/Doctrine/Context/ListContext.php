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
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ListContext implements ContextInterface
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
     * @param EventDispatcher $dispatcher
     * @param \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement $element
     */
    public function __construct(EventDispatcher $dispatcher, CRUDElement $element)
    {
        $this->dispatcher = $dispatcher;
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new AdminEvent($this->element, $request);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->element->getDataSource()->bindParameters($request);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $data = $this->element->getDataSource()->getResult();

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->element->getDataGrid()->setData($data);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->isMethod('POST'))  {
            $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_PRE_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->element->getDataGrid()->bindData($request);

            $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_POST_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->element->saveDataGrid();
            $this->element->getDataSource()->bindParameters($request);
            $data = $this->element->getDataSource()->getResult();
            $this->element->getDataGrid()->setData($data);
        }

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_RESPONSE_PRE_RENDER, $event);
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
        return $this->element->hasOption('template_crud_list');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_crud_list');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return array(
            'datagrid_view' => $this->element->getDataGrid()->createView(),
            'datasource_view' => $this->element->getDataSource()->createView(),
            'element' => $this->element,
            'title' => $this->element->getOption('crud_list_title')
        );
    }
}