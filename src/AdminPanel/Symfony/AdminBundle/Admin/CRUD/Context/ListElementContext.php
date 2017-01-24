<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextAbstract;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use Symfony\Component\HttpFoundation\Request;

class ListElementContext extends ContextAbstract
{
    /**
     * @var ListElement
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
     * {@inheritdoc}
     */
    public function setElement(Element $element)
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
        return $this->element->hasOption('template_list');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_list');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            'datagrid_view' => $this->dataGrid->createView(),
            'datasource_view' => $this->dataSource->createView(),
            'element' => $this->element,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent(Request $request)
    {
        return new ListEvent($this->element, $request, $this->dataSource, $this->dataGrid);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_list';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof ListElement;
    }
}
