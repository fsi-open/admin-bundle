<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement as AdminListElement;
use FSi\Bundle\AdminBundle\Behat\Element\Filters;
use FSi\Bundle\AdminBundle\Behat\Element\ListElement;
use FSi\Bundle\AdminBundle\Behat\Element\ListResultsElement;
use FSi\Bundle\AdminBundle\Behat\Page\DefaultPage;
use FSi\Component\DataSource\DataSourceInterface;

class FiltersContext extends AbstractContext
{
    /**
     * @var DefaultPage
     */
    private $defaultPage;

    /**
     * @var array<DataSourceInterface>
     */
    private $datasources = [];

    public function __construct(DefaultPage $defaultPage)
    {
        $this->defaultPage = $defaultPage;
    }

    /**
     * @Given /^("[^"]*" element) datasource max results is set (\d+)$/
     */
    public function elementDatasourceMaxResultsIsSet(AdminListElement $adminElement, $maxResults): void
    {
        expect($this->getDataSource($adminElement)->getMaxResults())->toBe($maxResults);
        $this->clearDataSource($adminElement);
    }

    /**
     * @Given /^("[^"]*" element) has datasource with fields$/
     */
    public function elementHaveDatasourceWithFields(AdminListElement $adminElement): void
    {
        $dataSource = $this->getDataSource($adminElement);

        expect(count($dataSource->getFields()) > 0)->toBe(true);
        $this->clearDataSource($adminElement);
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
        expect($filters)->toBe(false);

        $this->clearDataSource($adminElement);
    }

    /**
     * @Then /^both sorting buttons in column header "([^"]*)" should be active$/
     */
    public function bothSortingButtonsInColumnHeaderShouldBeActive($column): void
    {
        expect($this->getListElement()->isColumnAscSortActive($column))->toBe(true);
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
                expect($list->isColumnAscSortActive($column))->toBe(false);
                break;
            case 'sort desc':
                expect($list->isColumnDescSortActive($column))->toBe(false);
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
                expect($list->isColumnAscSortActive($column))->toBe(true);
                break;
            case 'sort desc':
                expect($list->isColumnDescSortActive($column))->toBe(true);
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
        expect($this->defaultPage->find('css', 'form.filters') === null)->toBe(true);
    }

    /**
     * @Then /^I should see simple text filter "([^"]*)"$/
     */
    public function iShouldSeeSimpleTextFilter($filterName): void
    {
        expect($this->getFiltersElement()->hasField($filterName))->toBe(true);
    }

    /**
     * @Given /^I should see between filter "([^"]*)" with "([^"]*)" and "([^"]*)" simple text fields$/
     */
    public function iShouldSeeBetweenFilterWithAndSimpleTextFields($filterName, $fromName, $toName): void
    {
        expect($this->getFiltersElement()->hasBetweenFilter($filterName, $fromName, $toName))->toBe(true);
    }

    /**
     * @Given /^I should see choice filter "([^"]*)"$/
     */
    public function iShouldSeeChoiceFilter($filterName): void
    {
        expect($this->getFiltersElement()->hasChoiceFilter($filterName))->toBe(true);
    }

    /**
     * @Given /^I fill simple text filter "([^"]*)" with value "([^"]*)"$/
     */
    public function iFillSimpleTextFilterWithValue($filterName, $filterValue): void
    {
        $this->getFiltersElement()->fillField($filterName, $filterValue);
    }

    /**
     * @When /^I select "([^"]*)" in choice filter "([^"]*)"$/
     */
    public function iSelectInChoiceFilter($filterValue, $filterName): void
    {
        $this->getFiltersElement()->findField($filterName)->selectOption($filterValue);
    }

    /**
     * @Given /^I press "Search" button$/
     */
    public function iPressSearchButton(): void
    {
        $this->getFiltersElement()->pressButton('Search');
    }

    /**
     * @Given /^simple text filter "([^"]*)" should be filled with value "([^"]*)"$/
     */
    public function simpleTextFilterShouldBeFilledWithValue($filterName, $filterValue): void
    {
        expect($this->getFiltersElement()->findField($filterName)->getValue())->toBe($filterValue);
    }

    /**
     * @Given /^choice filter "([^"]*)" should have value "([^"]*)" selected$/
     */
    public function choiceFilterShouldHaveValueSelected($filterName, $choice): void
    {
        $field = $this->getFiltersElement()->findField($filterName);
        expect($field->find('css', sprintf('option:contains("%s")', $choice))
            ->getAttribute('selected'))->toBe('selected');
    }

    /**
     * @Then /^I should see actions dropdown with following options$/
     */
    public function iShouldSeeActionsDropdownWithFollowingOptions(TableNode $actions): void
    {
        expect($this->defaultPage->hasBatchActionsDropdown())->toBe(true);

        foreach ($actions->getHash() as $actionRow) {
            expect($this->defaultPage->hasBatchAction($actionRow['Option']))->toBe(true);
        }
    }

    /**
     * @Given /^I should see confirmation button "([^"]*)"$/
     */
    public function iShouldSeeConfirmationButton($button): void
    {
        $this->defaultPage->hasButton($button);
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

    private function clearDataSource(AdminListElement $element): void
    {
        $this->getContainer()->get('test.datasource.factory')->clearDataSource($element->getId());
    }

    private function getFiltersElement(): Filters
    {
        return $this->defaultPage->getElement('Filters');
    }

    private function getListElement(): ListElement
    {
        return $this->defaultPage->getElement('ListElement');
    }

    private function getListResultsElement(): ListResultsElement
    {
        return $this->defaultPage->getElement('ListResultsElement');
    }
}
