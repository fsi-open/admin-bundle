<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class ListElementContext extends ContextAbstract
{
    /**
     * @var ListElement<array<string, mixed>|object>
     */
    protected ListElement $element;

    /**
     * @var DataSourceInterface<array<string, mixed>|object>
     */
    protected DataSourceInterface $dataSource;

    protected DataGridInterface $dataGrid;

    /**
     * @param ListElement<array<string, mixed>|object> $element
     */
    public function setElement(Element $element): void
    {
        if (false === $element instanceof ListElement) {
            /** @var class-string $givenClass */
            $givenClass = get_class($element);

            throw InvalidArgumentException::create(self::class, ListElement::class, $givenClass);
        }

        $this->element = $element;
        $this->dataSource = $this->element->createDataSource();
        $this->dataGrid = $this->element->createDataGrid();
    }

    public function getTemplateName(): ?string
    {
        return $this->element->hasOption('template_list')
            ? $this->element->getOption('template_list')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        return [
            'datagrid_view' => $this->dataGrid->createView(),
            'datasource_view' => $this->dataSource->createView(),
            'element' => $this->element,
        ];
    }

    protected function createEvent(Request $request): AdminEvent
    {
        return new ListEvent($this->element, $request, $this->dataSource, $this->dataGrid);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_list';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof ListElement;
    }
}
