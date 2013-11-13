<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context;

use Behat\Behat\Exception\BehaviorException;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\CRUDInterface;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class CRUDContext extends PageObjectContext implements KernelAwareInterface
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
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->datagrids = array();
        $this->datasources = array();
        $this->kernel = $kernel;
    }

    /**
     * @Transform /^"([^"]*)" element/
     */
    public function transformListNameToAdminElement($name)
    {
        switch ($name) {
            case 'news':
                return $this->kernel->getContainer()->get('admin.manager')->getElement('news');
                break;
            default:
                throw new BehaviorException(sprintf("Cant transform list name \"%s\" to admin element", $name));
                break;
        }
    }

    /**
     * @Transform /^(\d+)/
     */
    public function castStringToNumber($number)
    {
        return (int) $number;
    }

    /**
     * @Given /^following columns should be added to ("[^"]*" element) datagrid$/
     */
    public function followingColumnsShouldBeAddedToElementDatagrid(CRUDInterface $adminElement, TableNode $table)
    {
        $datagrid = $this->getDataGrid($adminElement);

        foreach ($table->getHash() as $columnRow) {
            expect($datagrid->hasColumn($columnRow['Column name']))->toBe(true);
            expect($datagrid->getColumn($columnRow['Column name'])->getId())->toBe($columnRow['Column type']);
        }
    }

    /**
     * @Given /^following options should be defined in ("[^"]*" element) datagrid columns$/
     */
    public function followingOptionsShouldBeDefinedInElementDatagridColumns(CRUDInterface $adminElement, TableNode $table)
    {
        $datagrid = $this->getDataGrid($adminElement);

        foreach ($table->getHash() as $columnRow) {
            $optionValue = $this->prepareColumnOptionValue($columnRow['Option value']);

            expect($datagrid->getColumn($columnRow['Column name'])->getOption($columnRow['Option name']))
                ->toBe($optionValue);
        }
    }

    /**
     * @Given /^following filters should be available at ("[^"]*" list) datasource$/
     */
    public function followingFiltersShouldBeAvailableAtListDatasource(CRUDInterface $adminElement, TableNode $table)
    {
        $datasource = $this->getDataSource($adminElement);

        foreach ($table->getHash() as $filterRow) {
            expect($datasource->hasField($filterRow['Filter name']))->toBe(true);
            expect($datasource->getField($filterRow['Filter name'])->getComparison())->toBe($filterRow['Comparison']);
            expect($datasource->getField($filterRow['Filter name'])->getType())->toBe($filterRow['Type']);
        }
    }

    /**
     * @Then /^I should see news list with following columns$/
     */
    public function iShouldSeeNewsListWithFollowingColumns(TableNode $table)
    {
        $newsList = $this->getPage('News list')->getElement('News list');

        foreach ($table->getHash() as $columnRow) {
            $newsList->hasColumn($columnRow['Column name']);
        }
    }

    /**
     * @Given /^("[^"]*" element) datasource max results is set (\d+)$/
     */
    public function elementDatasourceMaxResultsIsSet(CRUDInterface $adminElement, $maxResults)
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
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $adminElement
     * @return \FSi\Component\DataGrid\DataGrid
     */
    protected function getDataGrid(AbstractCRUD $adminElement)
    {
        if (!array_key_exists($adminElement->getId(), $this->datagrids)) {
            $this->datagrids[$adminElement->getId()] = $adminElement->createDataGrid();
        }

        return $this->datagrids[$adminElement->getId()];
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $adminElement
     * @return \FSi\Component\DataSource\DataSource
     */
    protected function getDataSource(AbstractCRUD $adminElement)
    {
        if (!array_key_exists($adminElement->getId(), $this->datasources)) {
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