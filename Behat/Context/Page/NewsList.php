<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class NewsList extends Page
{
    protected $path = '/admin/news/list';

    public function getHeader()
    {
        return $this->find('css', 'h3#page-header')->getText();
    }

    public function hasBatchActionsDropdown()
    {
        return $this->has('css', 'select#batch_action');
    }

    public function hasBatchAction($value)
    {
        $select = $this->find('css', 'select#batch_action');
        return $select->has('css', sprintf('option:contains("%s")', $value));
    }

    public function pressBatchCheckboxInRow($rowIndex)
    {
        $tr = $this->find('xpath', sprintf("descendant-or-self::table/tbody/tr[position() = %d]", $rowIndex));
        $tr->find('css', 'input[type="checkbox"]')->check();
    }

    public function pressBatchActionConfirmationButton()
    {
        $this->find('css', '#batch_action_confirmation')->click();
    }

    public function selectBatchAction($action)
    {
        $this->find('css', 'select#batch_action')->selectOption($action);
    }

    public function selectAllElements()
    {
        $th = $this->find('xpath', "descendant-or-self::table/thead/tr/th[position() = 1]");
        $th->find('css', 'input[type="checkbox"]')->click();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', 'h3#page-header:contains("List of elements")')) {
            throw new BehaviorException(sprintf("%s page is missing \"List of elements\" header", $this->path));
        }
    }
}