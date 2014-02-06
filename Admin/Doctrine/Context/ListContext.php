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
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ListContext implements ContextInterface
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
     * @var DataSource
     */
    protected $dataSource;

    /**
     * @var DataGrid
     */
    protected $dataGrid;

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
        $this->dataSource = $this->element->createDataSource();
        $this->dataGrid = $this->element->createDataGrid();
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
            'title' => $this->element->getOption('crud_list_title'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new ListEvent($this->element, $request, $this->dataSource, $this->dataGrid);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }

    /**
     * @param ListEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
    private function dataSourceBindParameters(ListEvent $event, Request $request)
    {
        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dataSource->bindParameters($request);
        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND, $event);

        if (!$event->hasResponse()) {
            return $this->dataGridSetData($event, $request);
        }

        return $event->getResponse();
    }

    /**
     * @param ListEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
    private function dataGridSetData(ListEvent $event, Request $request)
    {
        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->dataGrid->setData($this->dataSource->getResult());
        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND, $event);

        if (!$event->hasResponse()) {
            return $this->handlePostRequest($event, $request);
        }

        return $event->getResponse();
    }

    /**
     * @param ListEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
    private function handlePostRequest(ListEvent $event, Request $request)
    {
        if ($request->isMethod('POST')) {
            $response = $this->dataGridBindData($event, $request);
            if (isset($response)) {
                return $response;
            }
        }

        $this->dispatcher->dispatch(CRUDEvents::CRUD_LIST_RESPONSE_PRE_RENDER, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
    }

    /**
     * @param ListEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
    private function dataGridBindData(ListEvent $event, Request $request)
    private function dataGridBindData(ListEvent $event, Request $request)
    {
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

        return null;
    }
    */
}
