<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class ListEvent extends AdminEvent
{
    /**
     * @var DataSourceInterface<mixed>
     */
    protected DataSourceInterface $dataSource;

    protected DataGridInterface $dataGrid;

    /**
     * @return static
     */
    public static function fromOtherEvent(self $event): self
    {
        return new static($event->getElement(), $event->getRequest(), $event->getDataSource(), $event->getDataGrid());
    }

    /**
     * @param Element $element
     * @param Request $request
     * @param DataSourceInterface<mixed> $dataSource
     * @param DataGridInterface $dataGrid
     */
    final public function __construct(
        Element $element,
        Request $request,
        DataSourceInterface $dataSource,
        DataGridInterface $dataGrid
    ) {
        parent::__construct($element, $request);

        $this->dataSource = $dataSource;
        $this->dataGrid = $dataGrid;
    }

    /**
     * @return DataSourceInterface<mixed>
     */
    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }

    public function getDataGrid(): DataGridInterface
    {
        return $this->dataGrid;
    }
}
