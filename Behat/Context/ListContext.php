<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Exception;
use FSi\Bundle\AdminBundle\Behat\Element\ListElement;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Bundle\AdminBundle\Behat\Page\Page;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;

class ListContext extends AbstractContext
{
    private array $selectedRows = [];

    /**
     * @beforeScenario
     */
    public function clearSelectedRows(): void
    {
        $this->selectedRows = [];
    }

    /**
     * @When I check the row :name
     */
    public function iCheckTheRow($name): void
    {
        $this->selectedRows[] = $this->getListElement()->getNamedRowId($name);
    }

    /**
     * @When /^I press checkbox in first column in first row$/
     */
    public function iPressCheckboxInFirstColumnInFirstRow(): void
    {
        $this->selectedRows[] = $this->getListElement()->getRowId(1);
    }

    /**
     * @When /^I press checkbox in first column header$/
     */
    public function iPressCheckboxInFirstColumnHeader(): void
    {
        $this->selectedRows = array_unique(
            array_merge($this->selectedRows, $this->getListElement()->getRowsIds())
        );
    }

    /**
     * @Given I press :action link in actions column of first element at list
     */
    public function iPressLinkInColumnOfFirstElementAtList($link): void
    {
        $this->getListElement()->clickRowAction(1, $link);
    }

    /**
     * @Given I perform the batch action :action
     */
    public function iPerformBatchAction($action): void
    {
        if (false === $this->getSession()->isStarted()) {
            $this->getSession()->start();
        }

        if (true === $this->isSeleniumDriverUsed()) {
            $this->getSession()->getPage()->find('css', '#batch_action_action')->selectOption($action);
            $this->getSession()->getPage()->findButton('Ok')->click();
        } else {
            $batchActionUrl = $this->getSession()
                ->getPage()
                ->find('css', sprintf('#batch_action_action option:contains("%s")', $action))
                ->getAttribute('value');
            $data = [
                'batch_action' => [
                    '_token' => $this->getSession()
                        ->getPage()
                        ->find('css', '#batch_action__token')
                        ->getAttribute('value')
                ]
            ];
            $i = 0;
            foreach ($this->selectedRows as $id) {
                $data['indexes'][$i] = $id;
                $i++;
            }
            /** @var KernelBrowser $client */
            $client = $this->getSession()->getDriver()->getClient();
            $client->request(Request::METHOD_POST, $batchActionUrl, $data);
        }
    }

    /**
     * @Then /^I should see list with following columns$/
     */
    public function iShouldSeeListWithFollowingColumns(TableNode $table): void
    {
        $elements = $table->getColumn(0);
        array_shift($elements);

        $presentColumns = $this->getListElement()->getColumns();
        foreach ($elements as $expectedColumn) {
            if (
                strtolower($expectedColumn) === ListElement::BATCH_COLUMN
                && true === $this->getListElement()->hasBatchColumn()
            ) {
                continue;
            }
            if (false === in_array($expectedColumn, $presentColumns, true)) {
                throw new Exception(sprintf('there is no column with name "%s"', $expectedColumn));
            }
        }
    }

    /**
     * @Given /^there are (\d+) elements at list$/
     * @Given /^there is (\d+) element at list$/
     * @Then /^there should be (\d+) elements at list$/
     * @Then /^there should be (\d+) element at list$/
     */
    public function thereShouldBeElementsAtList($elemetsCount): void
    {
        Assertion::eq($this->getListElement()->getRowsCount(), $elemetsCount);
    }

    /**
     * @Given /^"([^"]*)" column is editable$/
     */
    public function columnIsEditable($columnHeader): void
    {
        Assertion::true($this->getListElement()->isColumnEditable($columnHeader));
    }

    /**
     * @Then I should not see pagination on page :page
     */
    public function iShouldNotSeePagination(Page $page): void
    {
        Assertion::null($this->getSession()->getPage()->find('css', 'ul.pagination'));
    }

    /**
     * @Given /^I clicked "([^"]*)" in "([^"]*)" column in first row$/
     * @When /^I click "([^"]*)" in "([^"]*)" column in first row$/
     */
    public function iClickInColumnInFirstRow($link, $columnHeader): void
    {
        $this->getListElement()->getCell($columnHeader, 1)->clickLink($link);
    }

    /**
     * @Given /^I clicked edit in "([^"]*)" column in first row$/
     * @When /^I click edit in "([^"]*)" column in first row$/
     */
    public function iClickEditInColumnInFirstRow($columnHeader): void
    {
        $cell = $this->getListElement()->getCell($columnHeader, 1);
        $cell->mouseOver();
        $cell->find('css', 'a[data-original-title="Edit"]')->click();
    }

    /**
     * @Then /^popover with "([^"]*)" field in form should appear$/
     */
    public function popoverWithFieldInFormShouldAppear($newsTitle): void
    {
        $popover = $this->getPage(AdminPanel::class)->getPopover();
        Assertion::true($popover->isVisible());
        Assertion::eq($popover->findField('Title')->getValue(), $newsTitle);
    }

    /**
     * @Then /^popover with empty date field in form should appear$/
     */
    public function popoverWithEmptyDateFieldInFormShouldAppear(): void
    {
        $popover = $this->getPage(AdminPanel::class)->getPopover();
        Assertion::true($popover->isVisible());
        Assertion::eq($popover->findField('Date')->getValue(), '');
    }
    /**
     * @Then /^popover should not be visible anymore$/
     */
    public function popoverShouldNotBeVisibleAnymore(): void
    {
        Assertion::null($this->getPage(AdminPanel::class)->getPopover());
    }

    /**
     * @When /^I fill "([^"]*)" field at popover with "([^"]*)" value$/
     */
    public function iFillFieldAtPopoverWithValue($field, $value): void
    {
        $this->getPage(AdminPanel::class)->getPopover()->fillField($field, $value);
    }

    /**
     * @Given /^I press "([^"]*)" at popover$/
     */
    public function iPressAtPopover($button): void
    {
        $this->getPage(AdminPanel::class)->getPopover()->pressButton($button);
    }

    /**
     * @When /^I click X at popover$/
     */
    public function iClickXAtPopover(): void
    {
        $session = $this->getSession();
        $session->wait(1000, 'jQuery(".popover").length > 0');
        /** @var NodeElement $popover */
        $popover = $this->getPage(AdminPanel::class)->getPopover();
        $popover->find('css', '.editable-close')->click();
        $session->wait(1000, 'jQuery(".popover").length === 0');
    }

    private function getListElement(): ListElement
    {
        return $this->getElement(ListElement::class);
    }
}
