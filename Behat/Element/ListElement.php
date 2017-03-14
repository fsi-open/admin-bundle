<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class ListElement extends Element
{
    const BATCH_COLUMN = 'batch';

    protected $selector = ['css' => 'body'];

    public function getColumns()
    {
        return $this->getNotEmptyTexts($this->getTable()->findAll('css', 'th'));
    }

    /**
     * @param string $name
     * @return NodeElement
     */
    public function getNamedColumn($name)
    {
        $selector = '//th[normalize-space(text())="%s"]/ancestor::tr';
        return $this->getTable()->find('xpath', sprintf($selector, $name));
    }

    public function hasBatchColumn()
    {
        return $this->has('css', 'th > input[type="checkbox"]');
    }

    /**
     * @return NodeElement[]
     */
    public function getRows()
    {
        return $this->getTable()->findAll('css', 'tbody > tr');
    }

    /**
     * @return string[]
     */
    public function getRowsIds()
    {
        $ids = [];
        foreach ($this->getRows() as $row) {
            $ids[] = $row->find('css', 'input[type=checkbox]')->getAttribute('value');
        }

        return $ids;
    }

    /**
     * @param int $number
     * @return NodeElement
     */
    public function getRow($number)
    {
        $row = $this->find('xpath', sprintf('//tbody/tr[%d]', $number));
        if (!isset($row)) {
            throw new UnexpectedPageException(sprintf('Row "%s" does not exist in DataGrid', $number));
        }

        return $row;
    }

    /**
     * @param string $number
     * @return string
     */
    public function getRowId($number)
    {
        return $this->getRow($number)->find('css', 'input[type=checkbox]')->getAttribute('value');
    }

    /**
     * @param string $name
     * @return string
     */
    public function getNamedRowId($name)
    {
        return $this->getNamedRow($name)->find('css', 'input[type=checkbox]')->getAttribute('value');
    }

    /**
     * @param string $name
     * @return NodeElement
     */
    public function getNamedRow($name)
    {
        $selector = '//span[@class="datagrid-cell-value" and normalize-space(text())="%s"]/ancestor::tr';
        return $this->getTable()->find('xpath', sprintf($selector, $name));
    }

    public function getRowsCount()
    {
        return count($this->getRows());
    }

    public function getCell($columnHeader, $rowNumber)
    {
        $columnPos = $this->getColumnPosition($columnHeader);
        return $this->find('xpath', sprintf("descendant-or-self::table/tbody/tr[%d]/td[%d]", $rowNumber, $columnPos));
    }

    public function getColumnPosition($columnHeader)
    {
        $headers = $this->findAll('css', 'th');
        foreach ($headers as $index => $header) {
            /** @var NodeElement $header */
            if ($header->has('css', 'span')
                && $header->find('css', 'span')->getText() == $columnHeader
            ) {
                return $index + 1;
            }
        }

        throw new UnexpectedPageException(sprintf("Cant find column %s", $columnHeader));
    }

    public function isColumnEditable($columnHeader)
    {
        return $this->getCell($columnHeader, 1)->has('css', 'a.editable');
    }

    public function isColumnSortable($columnHeader)
    {
        $column = $this->getColumnHeader($columnHeader);

        return $column->has('css', '.sort-asc') && $column->has('css', '.sort-desc');
    }

    public function isColumnAscSortActive($columnHeader)
    {
        $sortButton = $this->getColumnHeader($columnHeader)->find('css', '.sort-asc');

        return !$sortButton->hasAttribute('disabled');
    }

    public function isColumnDescSortActive($columnHeader)
    {
        $sortButton = $this->getColumnHeader($columnHeader)->find('css', '.sort-desc');

        return !$sortButton->hasAttribute('disabled');
    }

    public function pressSortButton($columnHeader, $sort)
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

    /**
     * @param int $rowNumber
     * @param string $action
     */
    public function clickRowAction($rowNumber, $action)
    {
        $this->getCell("Actions", $rowNumber)->clickLink($action);
    }

    private function getColumnHeader($columnHeader)
    {
        return $this->find('css', sprintf('th span:contains("%s")', $columnHeader))->getParent();
    }

    /**
     * @return NodeElement
     * @throws \Exception
     */
    private function getTable()
    {
        $table = $this->find('css', 'table.table-datagrid');
        if (empty($table)) {
            throw new \Exception('There is no datagrid table on page');
        }

        return $table;
    }

    private function getNotEmptyTexts(array $elements)
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
