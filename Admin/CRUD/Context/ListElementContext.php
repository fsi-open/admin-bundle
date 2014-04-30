<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use Symfony\Component\HttpFoundation\Request;

class ListElementContext implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

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
     * @param array $requestHandlers
     */
    public function __construct($requestHandlers)
    {
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * @param ListElement $element
     */
    public function setElement(ListElement $element)
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
        return array(
            'datagrid_view' => $this->dataGrid->createView(),
            'datasource_view' => $this->dataSource->createView(),
            'element' => $this->element,
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
}
