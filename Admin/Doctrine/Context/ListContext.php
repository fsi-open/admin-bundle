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
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
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
     * @var \FSi\Component\DataSource\DataSource
     */
    protected $dataSource;

    /**
     * @var \FSi\Component\DataGrid\DataGrid
     */
    protected $dataGrid;

    /**
     * @param EventDispatcher $dispatcher
     * @param \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement $element
     */
    public function __construct(EventDispatcher $dispatcher, CRUDElement $element)
    {
        $this->dispatcher = $dispatcher;
        $this->element = $element;
        $this->dataSource = $this->element->createDataSource();
        $this->dataGrid = $this->element->createDataGrid();
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new ListEvent($this->element, $request, $this->dataSource, $this->dataGrid);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dataSource->bindParameters($request);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $data = $this->dataSource->getResult();

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dataGrid->setData($data);

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->isMethod('POST'))  {
            $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_PRE_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->dataGrid->bindData($request);

            $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_POST_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->element->saveDataGrid();
            $this->dataSource->bindParameters($request);
            $data = $this->dataSource->getResult();
            $this->dataGrid->setData($data);
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
            'datagrid_view' => $this->dataGrid->createView(),
            'datasource_view' => $this->dataSource->createView(),
            'element' => $this->element,
            'title' => $this->element->getOption('crud_list_title')
        );
    }
}