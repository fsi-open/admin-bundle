<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\ListEvent;
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
        return $this->element->hasOption('template_list') || parent::hasTemplateName();
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->hasOption('template_list')
            ? $this->element->getOption('template_list')
            : parent::getTemplateName()
        ;
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
