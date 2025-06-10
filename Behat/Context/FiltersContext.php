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
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement as AdminListElement;
use FSi\Bundle\AdminBundle\Behat\Element\Filters;
use FSi\Bundle\AdminBundle\Behat\Element\ListElement;
use FSi\Bundle\AdminBundle\Behat\Element\ListResultsElement;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Component\DataSource\DataSourceInterface;

class FiltersContext extends AbstractContext
{
    /**
     * @var array<DataSourceInterface>
     */
    private array $datasources = [];

    /**
     * @Given /^("[^"]*" element) datasource max results is set (\d+)$/
     */
    public function elementDatasourceMaxResultsIsSet(AdminListElement $adminElement, $maxResults): void
    {
        Assertion::eq($this->getDataSource($adminElement)->getMaxResults(), $maxResults);
    }

    /**
     * @Given /^("[^"]*" element) has datasource with fields$/
     */
    public function elementHaveDatasourceWithFields(AdminListElement $adminElement): void
    {
        $dataSource = $this->getDataSource($adminElement);

        Assertion::true(count($dataSource->getFields()) > 0);
    }

    /**
     * @Given /^("[^"]*" element) has datasource without filters$/
     */
    public function elementHaveDatasourceWithoutFilters(AdminListElement $adminElement): void
    {
        $dataSource = $this->getDataSource($adminElement);

        $filters = false;
        foreach ($dataSource->getFields() as $field) {
            if ($field->getOption('form_filter')) {
                $filters = true;
                break;
            }
        }
        Assertion::false($filters);
    }

    /**
     * @Then /^both sorting buttons in column header "([^"]*)" should be active$/
     */
    public function bothSortingButtonsInColumnHeaderShouldBeActive($column): void
    {
        Assertion::true($this->getListElement()->isColumnAscSortActive($column));
    }

    /**
     * @When /^I press "([^"]*)" button in "([^"]*)" column header$/
     */
    public function iPressButtonInColumnHeader($sort, $column): void
    {
        $this->getListElement()->pressSortButton($column, $sort);
    }

    /**
     * @Then /^"([^"]*)" button in "([^"]*)" column header should be disabled$/
     */
    public function buttonInColumnHeaderShouldBeDisabled($sort, $column): void
    {
        $list = $this->getListElement();

        switch (strtolower($sort)) {
            case 'sort asc':
                Assertion::false($list->isColumnAscSortActive($column));
                break;
            case 'sort desc':
                Assertion::false($list->isColumnDescSortActive($column));
                break;
            default:
                throw new \LogicException(sprintf('Unknown sorting type %s', $sort));
        }
    }

    /**
     * @Given /^"([^"]*)" button in "([^"]*)" column header should be active$/
     */
    public function buttonInColumnHeaderShouldBeActive($sort, $column): void
    {
        $list = $this->getListElement();

        switch (strtolower($sort)) {
            case 'sort asc':
                Assertion::true($list->isColumnAscSortActive($column));
                break;
            case 'sort desc':
                Assertion::true($list->isColumnDescSortActive($column));
                break;
            default:
                throw new \LogicException(sprintf('Unknown sorting type %s', $sort));
        }
    }

    /**
     * @When /^I change elements per page to (\d+)$/
     */
    public function iChangeElementsPerPageTo($elementsCount): void
    {
        $this->getListResultsElement()->setElementsPerPage((int) $elementsCount);
    }

    /**
     * @Then /^I should not see any filters$/
     */
    public function iShouldNotSeeAnyFilters(): void
    {
        Assertion::null($this->getSession()->getPage()->find('css', 'form.filters'));
    }

    /**
     * @Then /^I should see simple text filter "([^"]*)"$/
     */
    public function iShouldSeeSimpleTextFilter($filterName): void
    {
        Assertion::true($this->getFiltersElement()->hasFilter($filterName));
    }

    /**
     * @Given /^I should see between filter "([^"]*)" with "([^"]*)" and "([^"]*)" simple text fields$/
     */
    public function iShouldSeeBetweenFilterWithAndSimpleTextFields($filterName, $fromName, $toName): void
    {
        Assertion::true($this->getFiltersElement()->hasBetweenFilter($filterName, $fromName, $toName));
    }

    /**
     * @Given /^I should see choice filter "([^"]*)"$/
     */
    public function iShouldSeeChoiceFilter($filterName): void
    {
        Assertion::true($this->getFiltersElement()->hasChoiceFilter($filterName));
    }

    /**
     * @Given /^I fill simple text filter "([^"]*)" with value "([^"]*)"$/
     */
    public function iFillSimpleTextFilterWithValue($filterName, $filterValue): void
    {
        $this->getFiltersElement()->setFilterValue($filterName, $filterValue);
    }

    /**
     * @When /^I select "([^"]*)" in choice filter "([^"]*)"$/
     */
    public function iSelectInChoiceFilter($filterValue, $filterName): void
    {
        $this->getFiltersElement()->setFilterOption($filterName, $filterValue);
    }

    /**
     * @Given /^I press "Search" button$/
     */
    public function iPressSearchButton(): void
    {
        $this->getFiltersElement()->submitFilters();
    }

    /**
     * @Given /^simple text filter "([^"]*)" should be filled with value "([^"]*)"$/
     */
    public function simpleTextFilterShouldBeFilledWithValue($filterName, $filterValue): void
    {
        Assertion::eq($this->getFiltersElement()->getFilerValue($filterName), $filterValue);
    }

    /**
     * @Given /^choice filter "([^"]*)" should have value "([^"]*)" selected$/
     */
    public function choiceFilterShouldHaveValueSelected($filterName, $choice): void
    {
        Assertion::eq($this->getFiltersElement()->getFilterOption($filterName), $choice);
    }

    /**
     * @Then /^I should see actions dropdown with following options$/
     */
    public function iShouldSeeActionsDropdownWithFollowingOptions(TableNode $actions): void
    {
        Assertion::true($this->getPage(AdminPanel::class)->hasBatchActionsDropdown());

        foreach ($actions->getHash() as $actionRow) {
            Assertion::true($this->getPage(AdminPanel::class)->hasBatchAction($actionRow['Option']));
        }
    }

    /**
     * @Given /^I should see confirmation button "([^"]*)"$/
     */
    public function iShouldSeeConfirmationButton($button): void
    {
        $this->getSession()->getPage()->hasButton($button);
    }

    /**
     * @param AdminListElement $adminElement
     * @return DataSourceInterface
     */
    private function getDataSource(AdminListElement $adminElement): DataSourceInterface
    {
        if (!isset($this->datasources[$adminElement->getId()])) {
            $this->datasources[$adminElement->getId()] = $adminElement->createDataSource();
        }

        return $this->datasources[$adminElement->getId()];
    }

    private function getFiltersElement(): Filters
    {
        return $this->getElement(Filters::class);
    }

    private function getListElement(): ListElement
    {
        return $this->getElement(ListElement::class);
    }

    private function getListResultsElement(): ListResultsElement
    {
        return $this->getElement(ListResultsElement::class);
    }
}
