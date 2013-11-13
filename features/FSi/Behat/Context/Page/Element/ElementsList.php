<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

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
        return true;
    }
}