<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Behat\Context\Page\NewsList;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class CRUDContext extends PageObjectContext implements KernelAwareContext, MinkAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var \FSi\Component\DataGrid\DataGrid[]
     */
    protected $datagrids;

    /**
     * @var \FSi\Component\DataSource\DataSource[]
     */
    protected $datasources;

    /**
     * @var Mink
     */
    protected $mink;

    /**
     * @var array
     */
    private $minkParameters;

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->datagrids = [];
        $this->datasources = [];
        $this->kernel = $kernel;
    }

    /**
     * @Given /^("[^"]*" element) datasource max results is set (\d+)$/
     */
    public function elementDatasourceMaxResultsIsSet(ListElement $adminElement, $maxResults)
    {
        $datasource = $this->getDataSource($adminElement);

        expect($datasource->getMaxResults())->toBe($maxResults);
    }

    /**
     * @Then /^I should see pagination with following buttons$/
     */
    public function iShouldSeePaginationWithFollowingButtons(TableNode $table)
    {
        $pagination = $this->getElement('Pagination');

        foreach ($table->getHash() as $buttonRow) {
            expect($pagination->hasLink($buttonRow['Button']))->toBe(true);

            if ($buttonRow['Active'] === 'true') {
                expect($pagination->isDisabled($buttonRow['Button']))->toBe(false);
            } else {
                expect($pagination->isDisabled($buttonRow['Button']))->toBe(true);
            }

            if ($buttonRow['Current'] === 'true') {
                expect($pagination->isCurrentPage($buttonRow['Button']))->toBe(true);
            } else {
                expect($pagination->isCurrentPage($buttonRow['Button']))->toBe(false);
            }
        }
    }

    /**
     * @Then /^both sorting buttons in column header "([^"]*)" should be active$/
     */
    public function bothSortingButtonsInColumnHeaderShouldBeActive($column)
    {
        expect($this->getElement('Elements List')->isColumnAscSortActive($column))->toBe(true);
    }

    /**
     * @When /^I press "([^"]*)" button in "([^"]*)" column header$/
     */
    public function iPressButtonInColumnHeader($sort, $column)
    {
        $this->getElement('Elements List')->pressSortButton($column, $sort);
    }

    /**
     * @Then /^"([^"]*)" button in "([^"]*)" column header should be disabled$/
     */
    public function buttonInColumnHeaderShouldBeDisabled($sort, $column)
    {
        $list = $this->getElement('Elements List');

        switch (strtolower($sort)) {
            case 'sort asc':
                expect($list->isColumnAscSortActive($column))->toBe(false);
                break;
            case 'sort desc':
                expect($list->isColumnDescSortActive($column))->toBe(false);
                break;
            default :
                throw new \Exception(sprintf("Unknown sorting type %s", $sort));
        }
    }

    /**
     * @Given /^"([^"]*)" button in "([^"]*)" column header should be active$/
     */
    public function buttonInColumnHeaderShouldBeActive($sort, $column)
    {
        $list = $this->getElement('Elements List');

        switch (strtolower($sort)) {
            case 'sort asc':
                expect($list->isColumnAscSortActive($column))->toBe(true);
                break;
            case 'sort desc':
                expect($list->isColumnDescSortActive($column))->toBe(true);
                break;
            default :
                throw new \Exception(sprintf("Unknown sorting type %s", $sort));
        }
    }

    /**
     * @When /^I press "([^"]*)" button at pagination$/
     */
    public function iPressButtonAtPagination($button)
    {
        $this->getElement('Pagination')->clickLink($button);
    }

    /**
     * @When /^I change elements per page to (\d+)$/
     */
    public function iChangeElementsPerPageTo($elemetsCount)
    {
        $this->getElement('Elements List Results')->setElementsPerPage($elemetsCount);
    }

    /**
     * @Then /^I should not see any filters$/
     */
    public function iShouldNotSeeAnyFilters()
    {
        expect($this->getPage('Custom News List')->find('css', 'form.filters') === null)->toBe(true);
    }

    /**
     * @Then /^I should see simple text filter "([^"]*)"$/
     */
    public function iShouldSeeSimpleTextFilter($filterName)
    {
        expect($this->getElement('Filters')->hasField($filterName))->toBe(true);
    }

    /**
     * @Given /^I should see between filter "([^"]*)" with "([^"]*)" and "([^"]*)" simple text fields$/
     */
    public function iShouldSeeBetweenFilterWithAndSimpleTextFields($filterName, $fromName, $toName)
    {
        expect($this->getElement('Filters')->hasBetweenFilter($filterName, $fromName, $toName))->toBe(true);
    }

    /**
     * @Given /^I should see choice filter "([^"]*)"$/
     */
    public function iShouldSeeChoiceFilter($filterName)
    {
        expect($this->getElement('Filters')->hasChoiceFilter($filterName))->toBe(true);
    }

    /**
     * @Given /^I fill simple text filter "([^"]*)" with value "([^"]*)"$/
     */
    public function iFillSimpleTextFilterWithValue($filterName, $filterValue)
    {
        $this->getElement('Filters')->fillField($filterName, $filterValue);
    }

    /**
     * @When /^I select "([^"]*)" in choice filter "([^"]*)"$/
     */
    public function iSelectInChoiceFilter($filterValue, $filterName)
    {
        $this->getElement('Filters')->findField($filterName)->selectOption($filterValue);
    }

    /**
     * @Given /^I press "Search" button$/
     */
    public function iPressSearchButton()
    {
        $this->getElement('Filters')->pressButton('Search');
    }

    /**
     * @Given /^I press "New element" link$/
     */
    public function iPressNewElementLink()
    {
        $this->getElement('New Element Link')->click();
    }

    /**
     * @Given /^simple text filter "([^"]*)" should be filled with value "([^"]*)"$/
     */
    public function simpleTextFilterShouldBeFilledWithValue($filterName, $filterValue)
    {
        expect($this->getElement('Filters')->findField($filterName)->getValue())->toBe($filterValue);
    }

    /**
     * @Given /^choice filter "([^"]*)" should have value "([^"]*)" selected$/
     */
    public function choiceFilterShouldHaveValueSelected($filterName, $choice)
    {
        $field = $this->getElement('Filters')->findField($filterName);
        expect($field->find('css', sprintf('option:contains("%s")', $choice))
            ->getAttribute('selected'))->toBe('selected');
    }

    /**
     * @Given /^I should be redirected to "([^"]*)" page$/
     */
    public function iShouldBeRedirectedToPage($pageName)
    {
        expect($this->getPage($pageName)->isOpen())->toBe(true);
    }

    /**
     * @Given /^I press "([^"]*)" link in "([^"]*)" column of first element at list$/
     */
    public function iPressLinkInColumnOfFirstElementAtList($link, $columnName)
    {
        $this->getElement('Elements list')->pressLinkInRowInColumn($link, 1, $columnName);
    }

    /**
     * @Then /^I should see customized "([^"]*)" view$/
     */
    public function iShouldSeeCustomizedView($crudElement)
    {
        switch($crudElement) {
            case 'subscribers list':
                $this->getPage('Custom subscribers list')->isOpen();
                break;
            case 'list':
                $this->getPage('Custom news list')->isOpen();
                break;
            case 'edit':
                $this->getPage('Custom news edit')->isOpen();
                break;
        }
    }

    /**
     * @Then /^I should see actions dropdown with following options$/
     */
    public function iShouldSeeActionsDropdownWithFollowingOptions(TableNode $actions)
    {
        expect($this->getPage('News list')->hasBatchActionsDropdown())->toBe(true);

        foreach ($actions->getHash() as $actionRow) {
            expect($this->getPage('News list')->hasBatchAction($actionRow['Option']))->toBe(true);
        }
    }

    /**
     * @Given /^I should see confirmation button "([^"]*)"$/
     */
    public function iShouldSeeConfirmationButton($button)
    {
        $this->getPage('News list')->hasButton($button);
    }

    /**
     * @Then /^I should be redirected to confirmation page with message$/
     */
    public function iShouldBeRedirectedToConfirmationPageWithMessage(PyStringNode $message)
    {
        $this->getPage('News delete confirmation')->isOpen();
        expect($this->getPage('News delete confirmation')->getConfirmationMessage())->toBe((string) $message);
    }

    /**
     * @Given /^I clicked "([^"]*)" in "([^"]*)" column in first row$/
     * @When /^I click "([^"]*)" in "([^"]*)" column in first row$/
     */
    public function iClickInColumnInFirstRow($link, $columnHeader)
    {
        $this->getPage('News list')->getCell($columnHeader, 1)->clickLink($link);
    }

    /**
     * @Given /^I clicked edit in "([^"]*)" column in first row$/
     * @When /^I click edit in "([^"]*)" column in first row$/
     */
    public function iClickEditInColumnInFirstRow($columnHeader)
    {
        $cell = $this->getPage('News list')->getCell($columnHeader, 1);
        $this->getPage('News list')->getSession()->getDriver()->click($cell->getXPath());
        $cell->find('css', 'a')->click();
    }

    /**
     * @Then /^popover with "([^"]*)" field in form should appear$/
     */
    public function popoverWithFieldInFormShouldAppear($newsTitle)
    {
        $popover = $this->getPage('News list')->getPopover();
        expect($popover->isVisible())->toBe(true);
        expect($popover->findField('Title')->getValue())->toBe($newsTitle);
    }

    /**
     * @Then /^popover with empty date field in form should appear$/
     */
    public function popoverWithEmptyDateFieldInFormShouldAppear()
    {
        $popover = $this->getPage('News list')->getPopover();
        expect($popover->isVisible())->toBe(true);
        expect($popover->findField('Date')->getValue())->toBe('');
    }

    /**
     * @When /^I click X at popover$/
     */
    public function iClickXAtPopover()
    {
        /** @var NewsList $page */
        $page = $this->getPage('News list');
        $session = $page->getSession();
        /** @var NodeElement $popover */
        $session->wait(1000, 'jQuery(".popover").length > 0');
        $popover = $page->getPopover();
        $popover->find('css', '.editable-close')->click();
        $session->wait(1000, 'jQuery(".popover").length === 0');
    }

    /**
     * @Then /^popover should not be visible anymore$/
     */
    public function popoverShouldNotBeVisibleAnymore()
    {
        expect($this->getPage('News list')->getPopover())->toBe(null);
    }

    /**
     * @When /^I fill "([^"]*)" field at popover with "([^"]*)" value$/
     */
    public function iFillFieldAtPopoverWithValue($field, $value)
    {
        $this->getPage('News list')->getPopover()->fillField($field, $value);
    }

    /**
     * @Given /^I press "([^"]*)" at popover$/
     */
    public function iPressAtPopover($button)
    {
        $this->getPage('News list')->getPopover()->pressButton($button);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $adminElement
     * @return \FSi\Component\DataGrid\DataGrid
     */
    protected function getDataGrid(ListElement $adminElement)
    {
        if (!array_key_exists($adminElement->getId(), $this->datagrids)) {
            $this->datagrids[$adminElement->getId()] = $adminElement->createDataGrid();
        }

        return $this->datagrids[$adminElement->getId()];
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $adminElement
     * @return \FSi\Component\DataSource\DataSource
     */
    protected function getDataSource(ListElement $adminElement)
    {
        if (!isset($this->datasources[$adminElement->getId()])) {
            $this->datasources[$adminElement->getId()] = $adminElement->createDataSource();
        }

        return $this->datasources[$adminElement->getId()];
    }
}
