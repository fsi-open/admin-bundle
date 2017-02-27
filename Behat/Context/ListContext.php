<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminBundle\Behat\Element\ListElement;
use FSi\Bundle\AdminBundle\Behat\Page\DefaultPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Symfony\Component\BrowserKit\Client;

class ListContext extends AbstractContext
{
    /**
     * @var DefaultPage
     */
    private $defaultPage;

    /**
     * @var array
     */
    private $selectedRows = [];

    public function __construct(DefaultPage $defaultPage)
    {
        $this->defaultPage = $defaultPage;
    }

    /**
     * @beforeScenario
     */
    public function clearSelectedRows()
    {
        $this->selectedRows = [];
    }

    /**
     * @When I check the row :name
     */
    public function iCheckTheRow($name)
    {
        $this->selectedRows[] = $this->getListElement()->getNamedRowId($name);
    }

    /**
     * @When /^I press checkbox in first column in first row$/
     */
    public function iPressCheckboxInFirstColumnInFirstRow()
    {
        $this->selectedRows[] = $this->getListElement()->getRowId(1);
    }

    /**
     * @When /^I press checkbox in first column header$/
     */
    public function iPressCheckboxInFirstColumnHeader()
    {
        $this->selectedRows = array_unique(
            array_merge($this->selectedRows, $this->getListElement()->getRowsIds())
        );
    }

    /**
     * @Given I perform the batch action :action
     */
    public function iPerformBatchAction($action)
    {
        if ($this->isSeleniumDriverUsed()) {
            $this->defaultPage->find('css', '#batch_action_action')->selectOption($action);
            $this->defaultPage->findButton('Ok')->click();
        } else {
            $batchActionUrl = $this->defaultPage
                ->find('css', sprintf('#batch_action_action option:contains("%s")', $action))
                ->getAttribute('value');
            $data = [
                'batch_action' => [
                    '_token' => $this->defaultPage->find('css', '#batch_action__token')->getAttribute('value')
                ]
            ];
            $i = 0;
            foreach ($this->selectedRows as $id) {
                $data['indexes'][$i] = $id;
                $i++;
            }
            /** @var Client $client */
            $client = $this->getSession()->getDriver()->getClient();
            $client->request('POST', $batchActionUrl, $data);
        }
    }

    /**
     * @Then /^I should see list with following columns$/
     */
    public function iShouldSeeListWithFollowingColumns(TableNode $table)
    {
        $elements = $table->getColumn(0);
        array_shift($elements);

        $presentColumns = $this->getListElement()->getColumns();
        foreach ($elements as $expectedColumn) {
            if (strtolower($expectedColumn) === ListElement::BATCH_COLUMN
                && $this->getListElement()->hasBatchColumn()
            ) {
                continue;
            }
            if (!in_array($expectedColumn, $presentColumns)) {
                throw new \Exception(sprintf('there is no column with name "%s"', $expectedColumn));
            }
        }
    }

    /**
     * @Given /^there are (\d+) elements at list$/
     * @Given /^there is (\d+) element at list$/
     * @Then /^there should be (\d+) elements at list$/
     * @Then /^there should be (\d+) element at list$/
     */
    public function thereShouldBeElementsAtList($elemetsCount)
    {
        expect($this->getListElement()->getRowsCount())->toBe($elemetsCount);
    }

    /**
     * @Given /^"([^"]*)" column is editable$/
     */
    public function columnIsEditable($columnHeader)
    {
        expect($this->getListElement()->isColumnEditable($columnHeader))->toBe(true);
    }

    /**
     * @Then I should not see pagination on page :page
     */
    public function iShouldNotSeePagination(Page $page)
    {
        expect($page->find('css', 'ul.pagination'))->toBe(null);
    }

    /**
     * @return ListElement
     */
    private function getListElement()
    {
        return $this->defaultPage->getElement('ListElement');
    }
}
