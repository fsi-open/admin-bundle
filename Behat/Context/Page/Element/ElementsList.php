<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class ElementsList extends Element
{
    protected $selector = array('css' => 'table.table.table-hover.table-striped.table-bordered');

    public function getElementsCount()
    {
        return count($this->findAll('css', 'tbody > tr'));
    }

    public function hasColumn($columnHeader)
    {
        if (strtolower($columnHeader) == 'batch') {
            return $this->has('css', 'th > input[type="checkbox"]');
        }

        return $this->has('css', sprintf('th span:contains("%s")', $columnHeader));
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
                break;
        }
    }

    public function pressLinkInRowInColumn($link, $row, $columnName)
    {
        $position = $this->getColumnPosition($columnName);
        $cell = $this->find('css', sprintf('tbody tr:nth-of-type(%d) td:nth-of-type(%d)', (int) $row, $position));
        $cell->clickLink($link);
    }

    protected function getColumnHeader($columnHeader)
    {
        if (strtolower($columnHeader) == 'batch') {
            $column = $this->find('css', 'th > input[type="checkbox"]');
        } else {
            $column = $this->find('css', sprintf('th span:contains("%s")', $columnHeader));
        }

        return $column->getParent();
    }

    /**
     * @param $columnName
     * @return int
     */
    private function getColumnPosition($columnName)
    {
        $position = 1;
        $columnHeaders = $this->findAll('css', 'th');
        foreach ($columnHeaders as $columnHeader) {
            if ($columnHeader->has('css', sprintf('span:contains("%s")', $columnName))) {
                break;
            }
            $position++;
        }
        return $position;
    }
}
