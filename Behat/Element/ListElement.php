<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class ListElement extends Element
{
    public const BATCH_COLUMN = 'batch';

    protected $selector = ['css' => 'body'];

    /**
     * @return NodeElement[]
     */
    public function getColumns(): array
    {
        return $this->getNotEmptyTexts($this->getTable()->findAll('css', 'th'));
    }

    /**
     * @param string $name
     * @return NodeElement|null
     */
    public function getNamedColumn(string $name): ?NodeElement
    {
        $selector = '//th[normalize-space(text())="%s"]/ancestor::tr';
        return $this->getTable()->find('xpath', sprintf($selector, $name));
    }

    public function hasBatchColumn(): bool
    {
        return $this->has('css', 'th > input[type="checkbox"]');
    }

    /**
     * @return NodeElement[]
     */
    public function getRows(): array
    {
        return $this->getTable()->findAll('css', 'tbody > tr');
    }

    /**
     * @return string[]
     */
    public function getRowsIds(): array
    {
        $ids = [];
        foreach ($this->getRows() as $row) {
            $ids[] = $row->find('css', 'input[type=checkbox]')->getAttribute('value');
        }

        return $ids;
    }

    public function getRow(int $number): NodeElement
    {
        $row = $this->find('xpath', sprintf('//tbody/tr[%d]', $number));
        if (!isset($row)) {
            throw new UnexpectedPageException(sprintf('Row "%s" does not exist in DataGrid', $number));
        }

        return $row;
    }

    public function getRowId(int $number): string
    {
        return $this->getRow($number)->find('css', 'input[type=checkbox]')->getAttribute('value');
    }

    public function getNamedRowId(string $name): string
    {
        return $this->getNamedRow($name)->find('css', 'input[type=checkbox]')->getAttribute('value');
    }

    /**
     * @param string $name
     * @return NodeElement
     */
    public function getNamedRow(string $name): NodeElement
    {
        $selector = '//span[@class="datagrid-cell-value" and normalize-space(text())="%s"]/ancestor::tr';

        return $this->getTable()->find('xpath', sprintf($selector, $name));
    }

    public function getRowsCount(): int
    {
        return count($this->getRows());
    }

    public function getCell($columnHeader, $rowNumber): ?NodeElement
    {
        $columnPos = $this->getColumnPosition($columnHeader);
        return $this->find('xpath', sprintf('descendant-or-self::table/tbody/tr[%d]/td[%d]', $rowNumber, $columnPos));
    }

    public function getColumnPosition(string $columnHeader): int
    {
        $headers = $this->findAll('css', 'th');
        foreach ($headers as $index => $header) {
            /** @var NodeElement $header */
            if (
                $header->has('css', 'span')
                && $header->find('css', 'span')->getText() === $columnHeader
            ) {
                return $index + 1;
            }
        }

        throw new UnexpectedPageException(sprintf('Can\'t find column %s', $columnHeader));
    }

    public function isColumnEditable(string $columnHeader): bool
    {
        return $this->getCell($columnHeader, 1)->has('css', 'a.editable');
    }

    public function isColumnSortable(string $columnHeader): bool
    {
        $column = $this->getColumnHeader($columnHeader);

        return $column->has('css', '.sort-asc') && $column->has('css', '.sort-desc');
    }

    public function isColumnAscSortActive(string $columnHeader): bool
    {
        $sortButton = $this->getColumnHeader($columnHeader)->find('css', '.sort-asc');

        return !$sortButton->hasAttribute('disabled');
    }

    public function isColumnDescSortActive(string $columnHeader): bool
    {
        $sortButton = $this->getColumnHeader($columnHeader)->find('css', '.sort-desc');

        return !$sortButton->hasAttribute('disabled');
    }

    public function pressSortButton(string $columnHeader, string $sort): void
    {
        switch (strtolower($sort)) {
            case 'sort asc':
                $this->getColumnHeader($columnHeader)->find('css', '.sort-asc')->click();
                break;
            case 'sort desc':
                $this->getColumnHeader($columnHeader)->find('css', '.sort-desc')->click();
                break;
            default:
                throw new UnexpectedPageException(sprintf("Unknown sorting type %s", $sort));
        }
    }

    public function clickRowAction(int $rowNumber, string $action): void
    {
        $this->getCell('Actions', $rowNumber)->clickLink($action);
    }

    private function getColumnHeader(string $columnHeader): ?NodeElement
    {
        return $this->find('css', sprintf('th span:contains("%s")', $columnHeader))->getParent();
    }

    private function getTable(): NodeElement
    {
        $table = $this->find('css', 'table.table-datagrid');
        if (null === $table) {
            throw new UnexpectedPageException('There is no datagrid table on page');
        }

        return $table;
    }

    /**
     * @param NodeElement[] $elements
     * @return string[]
     */
    private function getNotEmptyTexts(array $elements): array
    {
        $texts = [];
        /** @var Element $column */
        foreach ($elements as $column) {
            $text = $column->getText();
            if (!empty($text)) {
                $texts[] = $text;
            }
        }
        return $texts;
    }
}
