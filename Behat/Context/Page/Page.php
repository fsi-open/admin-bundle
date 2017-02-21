<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page as BasePage;

class Page extends BasePage
{
    public function getCollection($label)
    {
        return $this->find('xpath', sprintf('//div[@data-prototype]/ancestor::*[@class = "form-group"]/label[text() = "%s"]/..//div[@data-prototype]', $label));
    }

    public function getNoneditableCollection($label)
    {
        return $this->find('xpath', sprintf('//div[@data-prototype-name]/ancestor::*[@class = "form-group"]/label[text() = "%s"]/..//div[@data-prototype-name]', $label));
    }

    public function openWithoutVerifying(array $urlParameters = array())
    {
        $url = $this->getUrl($urlParameters);
        $this->getDriver()->visit($url);
        return $this;
    }

    public function getStatusCode()
    {
        return $this->getDriver()->getStatusCode();
    }

    public function hasBatchActionsDropdown()
    {
        return $this->has('css', 'select[data-datagrid-name]');
    }

    public function hasBatchAction($value)
    {
        $select = $this->find('css', 'select[data-datagrid-name]');
        return $select->has('css', sprintf('option:contains("%s")', $value));
    }

    public function pressBatchCheckboxInRow($rowIndex)
    {
        $tr = $this->find('xpath', sprintf("descendant-or-self::table/tbody/tr[position() = %d]", $rowIndex));
        $tr->find('css', 'input[type="checkbox"]')->check();
    }

    public function pressBatchActionConfirmationButton()
    {
        $this->find('css', 'button[data-datagrid-name]')->click();
    }

    public function selectBatchAction($action)
    {
        $this->find('css', 'select[data-datagrid-name]')->selectOption($action);
    }

    public function selectAllElements()
    {
        $th = $this->find('xpath', "descendant-or-self::table/thead/tr/th[position() = 1]");
        $th->find('css', 'input[type="checkbox"]')->click();
    }

    public function isColumnEditable($columnHeader)
    {
        return $this->getCell($columnHeader, 1)->has('css', 'a.editable');
    }

    public function getColumnPosition($columnHeader)
    {
        $headers = $this->findAll('css', 'th');
        foreach ($headers as $index => $header) {
            /** @var NodeElement $header */
            if ($header->has('css', 'span')) {
                if ($header->find('css', 'span')->getText() == $columnHeader) {
                    return $index + 1;
                }
            }
        }

        throw new UnexpectedPageException(sprintf("Cant find column %s", $columnHeader));
    }

    public function getCell($columnHeader, $rowNumber)
    {
        $columnPos = $this->getColumnPosition($columnHeader);
        return $this->find('xpath', sprintf("descendant-or-self::table/tbody/tr[%d]/td[%d]", $rowNumber, $columnPos));
    }

    public function getPopover()
    {
        return $this->find('css', '.popover');
    }
}
