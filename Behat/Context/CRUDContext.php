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
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Faker\Factory;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Behat\Context\Page\NewsList;
use PhpSpec\Exception\Example\PendingException;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\BrowserKit\Client;
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
     * @var string
     */
    protected $newsTitle;

    /**
     * @var string
     */
    protected $subscriberEmail;

    /**
     * @var string
     */
    protected $invalidEmail = 'not_a_valid_email#email.';

    /**
     * @var Mink
     */
    protected $mink;

    /**
     * @var array
     */
    private $minkParameters;

    private $selectedRows = [];

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
     * @Then /^I should see list with following columns$/
     */
    public function iShouldSeeListWithFollowingColumns(TableNode $table)
    {
        $list = $this->getElement('Elements List');

        foreach ($table->getHash() as $columnRow) {
            expect($list->hasColumn($columnRow['Column name']))->toBe(true);

            if (array_key_exists('Sortable', $columnRow)) {
                expect($list->isColumnSortable($columnRow['Column name']))
                    ->toBe(($columnRow['Sortable'] === 'true') ? true : false);
            }
        }
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
                break;
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
                break;
        }
    }

    /**
     * @Then /^I should not see pagination$/
     */
    public function iShouldNotSeePagination()
    {
        expect($this->getElement('Pagination'))->toThrow('\LogicException');
    }

    /**
     * @When /^I press "([^"]*)" button at pagination$/
     */
    public function iPressButtonAtPagination($button)
    {
        $this->getElement('Pagination')->clickLink($button);
    }

    /**
     * @Then /^there should be (\d+) elements at list$/
     */
    public function thereShouldBeElementsAtList($elemetsCount)
    {
        expect($this->getElement('Elements List')->getElementsCount())->toBe($elemetsCount);
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
     * @Then /^I should see filtered list$/
     */
    public function iShouldSeeFilteredList()
    {
        $this->getElement('Elements List')->getHtml();
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
     * @Given /^I should see form with following fields$/
     */
    public function iShouldSeeFormWithFollowingFields(TableNode $table)
    {
        $form = $this->getElement('Form');
        foreach($table->getHash() as $fieldRow) {
            expect($form->hasField($fieldRow['Field name']))->toBe(true);
        }
    }

    /**
     * @When /^I fill all form field properly$/
     */
    public function iFillAllFormFieldProperly()
    {
        $generator = Factory::create();
        $form = $this->getElement('Form');
        if ($form->hasField('Title')) {
            $form->fillField('Title', $generator->text());
        }
        if ($form->hasField('Created at')) {
            $this->getElement('Form')->fillField('Created at', $generator->date());
        }
        if ($form->hasField('Visible')) {
            if ($generator->boolean()) {
                $this->getElement('Form')->checkField('Visible');
            } else {
                $this->getElement('Form')->uncheckField('Visible');
            }
        }
        if ($form->hasField('Active')) {
            $this->getElement('Form')->fillField('Active', $generator->boolean());
        }
        if ($form->hasField('Email')) {
            $this->getElement('Form')->fillField('Email', $generator->email());
        }
        if ($form->hasField('Creator email')) {
            $this->getElement('Form')->fillField('Creator email', $generator->email());
        }
    }

    /**
     * @Given /^I press form "([^"]*)" button$/
     */
    public function iPressFormButton($button)
    {
        $this->getElement('Form')->pressButton($button);
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
     * @When /^I change form "([^"]*)" field value$/
     */
    public function iChangeFormTitleFieldValue($field)
    {
        $generator = Factory::create();
        switch ($field) {
            case 'Title':
                $this->newsTitle = $generator->text();
                expect($this->newsTitle)->toNotBe($this->getElement('Form')->findField($field)->getValue());
                $this->getElement('Form')->fillField($field, $this->newsTitle);
                break;
            case 'Email':
                $this->subscriberEmail = $generator->email();
                expect($this->subscriberEmail)->toNotBe($this->getElement('Form')->findField($field)->getValue());
                $this->getElement('Form')->fillField($field, $this->subscriberEmail);
                break;
        }
    }

    /**
     * @When /^I fill the "([^"]*)" field with invalid data$/
     */
    public function iFillFormValueWithInvalidData($field)
    {
        switch ($field) {
            case 'Email':
                expect($this->invalidEmail)->toNotBe($this->getElement('Form')->findField($field)->getValue());
                $this->getElement('Form')->fillField($field, $this->invalidEmail);
                break;
        }
    }

    /**
     * @Then /^news with id (\d+) should have changed title$/
     */
    public function newsWithIdShouldHaveChangedTitle($id)
    {
        expect($this->kernel->getContainer()->get('doctrine')->getManager()->getRepository('FSi\FixturesBundle\Entity\News')->findOneById($id)->getTitle())->toBe($this->newsTitle);
    }

    /**
     * @Then /^subscriber with id (\d+) should have changed email$/
     */
    public function subscriberWithIdShouldHaveChangedEmail($id)
    {
        expect($this->kernel->getContainer()->get('doctrine')->getManager()->getRepository('FSi\FixturesBundle\Entity\Subscriber')->findOneById($id)->getEmail())->toBe($this->subscriberEmail);
    }

    /**
     * @Then /^subscriber with id (\d+) should not have his email changed to invalid one$/
     */
    public function subscriberWithIdShouldNotHaveChangedEmailToInvalid($id)
    {
        $manager = $this->kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $subsciber = $manager
            ->getRepository('FSi\FixturesBundle\Entity\Subscriber')
            ->findOneById($id)
        ;
        $manager->refresh($subsciber);
        expect($subsciber->getEmail())->notToBe($this->invalidEmail);
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
     * @When /^I press checkbox in first column in first row$/
     */
    public function iPressCheckboxInFirstColumnInFirstRow()
    {
        $driver = $this->mink->getSession()->getDriver();
        if ($driver instanceof Selenium2Driver) {
            $this->getPage('News list')->pressBatchCheckboxInRow(1);
        } else {
            $row = $this->getPage('News list')->find('xpath', sprintf('//tr[%d]', 1));
            $this->selectedRows[] = $row->find('css', 'input[type=checkbox]')->getAttribute('value');
        }
    }

    /**
     * @Given /^I choose action "([^"]*)" from actions$/
     */
    public function iChooseActionFromActions($action)
    {
        $this->getPage('News list')->selectBatchAction($action);
    }

    /**
     * @Given /^I press confirmation button "Ok"$/
     */
    public function iPressConfirmationButton()
    {
        $this->getPage('News list')->pressBatchActionConfirmationButton();
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
     * @When /^I press "Yes"$/
     */
    public function iPress()
    {
        $this->getPage('News delete confirmation')->pressButton('Yes');
    }

    /**
     * @When /^I press checkbox in first column header$/
     */
    public function iPressCheckboxInFirstColumnHeader()
    {
        $this->getPage('News list')->selectAllElements();
    }

    /**
     * @Given /^"([^"]*)" column is editable$/
     */
    public function columnIsEditable($columnHeader)
    {
        expect($this->getPage('News list')->isColumnEditable($columnHeader))->toBe(true);
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
     * @Then /^"([^"]*)" news title should be changed to "([^"]*)"$/
     */
    public function newsTitleShouldBeChangedTo($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Given I perform action :action
     */
    public function iPerformBatchActionOnPage($action)
    {
        $page = $this->getPage('News list');
        $batchActionUrl = $page->find('css', sprintf('#batch_action_action option:contains("%s")', $action))
            ->getAttribute('value')
        ;
        $data = [
            'batch_action' => [
                '_token' => $page->find('css', '#batch_action__token')
                    ->getAttribute('value'),
            ],
        ];
        foreach ($this->selectedRows as $id) {
            $data['indexes'][] = $id;
        }

        /* @var $client Client */
        $client = $this->mink->getSession()->getDriver()->getClient();
        $client->request('POST', $batchActionUrl, $data);
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

    /**
     * @param $optionValue
     * @return mixed
     */
    protected function prepareColumnOptionValue($optionValue)
    {
        if ($optionValue === 'true' || $optionValue === 'false') {
            $optionValue = ($optionValue === 'true') ? true : false;
        }

        return $optionValue;
    }
}
