<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class AdminContext extends PageObjectContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    function __construct()
    {
        $this->useContext('CRUD', new CRUDContext());
        $this->useContext('resource', new ResourceContext());
        $this->useContext('data', new DataContext());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Transform /^"([^"]*)" element/
     */
    public function transformListNameToAdminElement($id)
    {
        return $this->kernel->getContainer()->get('admin.manager')->getElement($id);
    }

    /**
     * @Transform /^(\d+)/
     */
    public function castStringToNumber($number)
    {
        return (int) $number;
    }

    /**
     * @Given /^the following services were registered$/
     */
    public function theFollowingServicesWereRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $serviceRow) {
            expect($this->kernel->getContainer()->has($serviceRow['Id']))->toBe(true);
            expect($this->kernel->getContainer()->get($serviceRow['Id']))->toBeAnInstanceOf($serviceRow['Class']);
        }
    }

    /**
     * @Given /^("[^"]*" element) have following options defined$/
     */
    public function elementHaveFollowingOptionsDefined(AbstractList $adminElement, TableNode $options)
    {
        foreach ($options->getHash() as $optionRow) {
            expect($adminElement->hasOption($optionRow['Option']))->toBe(true);
            expect($adminElement->getOption($optionRow['Option']))->toBe($optionRow['Value']);
        }
    }

    /**
     * @Given /^there are following admin elements available$/
     */
    public function thereAreFollowingAdminElementsAvailable(TableNode $table)
    {
        foreach ($table->getHash() as $elementRow) {
            expect($this->kernel->getContainer()->get('admin.manager')->hasElement($elementRow['Id']))
                ->toBe(true);
            expect($this->kernel->getContainer()->get('admin.manager')->getElement($elementRow['Id'])->getName())
                ->toBe($elementRow['Name']);
        }
    }

    /**
     * @Given /^I am on the "([^"]*)" page$/
     */
    public function iAmOnThePage($pageName)
    {
        $this->getPage($pageName)->open();
    }

    /**
     * @Given /^I am on the "([^"]*)" page with id (\d+)$/
     */
    public function iAmOnThePageWithId($pageName, $id)
    {
        $this->getPage($pageName)->open(array('id' => $id));
    }

    /**
     * @When /^I follow "([^"]*)" url from top bar$/
     * @Given /^I follow "([^"]*)" menu element$/
     */
    public function iFollowUrlFromTopBar($menuElement)
    {
        $this->getPage('Admin Panel')->getMenu()->clickLink($menuElement);
    }

    /**
     * @Then /^I should see "([^"]*)" title at top bar$/
     */
    public function iShouldSeeTitleAtTopBar($navbarBrandText)
    {
        expect($this->getPage('Admin Panel')->getNavbarBrandText())->toBe($navbarBrandText);
    }

    /**
     * @Then /^menu with following elements should be visible at the top of the page$/
     */
    public function menuWithFollowingElementsShouldBeVisibleAtTheTopOfThePage(TableNode $table)
    {
        $page = $this->getPage('Admin Panel');

        expect($page->getMenuElementsCount())->toBe(count($table->getHash()));

        foreach ($table->getHash() as $elementRow) {
            expect($page->hasMenuElement(
                $elementRow['Element name'],
                empty($elementRow['Element group']) ? null : $elementRow['Element group'])
            )->toBe(true);
        }
    }

    /**
     * @Given /^I should see "([^"]*)" page header "([^"]*)"$/
     */
    public function iShouldSeePageHeader($pageName, $headerContent)
    {
        expect($this->getPage($pageName)->getHeader())->toBe($headerContent);
    }

    /**
     * @Given /^translations are enabled in application$/
     */
    public function translationsAreEnabledInApplication()
    {
        expect($this->kernel->getContainer()->get('translator'))->toBeAnInstanceOf('Symfony\Bundle\FrameworkBundle\Translation\Translator');
    }


    /**
     * @Then /^I should see language dropdown button in navigation bar with text "([^"]*)"$/
     * @Then /^I should see language dropdown button with text "([^"]*)"$/
     */
    public function iShouldSeeLanguageDropdownButtonInNavigationBarWithText($button)
    {
        expect($this->getPage('Admin panel')->getLanguageDropdown()->hasLink($button))->toBe(true);
    }

    /**
     * @Given /^language dropdown button should have following links$/
     */
    public function languageDropdownButtonShouldHaveFollowingLinks(TableNode $dropdownLinks)
    {
        $links = $this->getPage('Admin panel')->getLanguageDropdownOptions();

        foreach ($dropdownLinks->getHash() as $link) {
            expect($links)->toContain($link['Link']);
        }

    }

    /**
     * @When /^I click "([^"]*)" link from language dropdown button$/
     */
    public function iClickLinkFromLanguageDropdownButton($link)
    {
        $this->getPage('Admin panel')->getLanguageDropdown()->clickLink($link);
    }

    /**
     * @Then /^I should see details about news at page$/
     */
    public function iShouldSeeDetailsAboutNewsAtPage()
    {
        expect($this->getPage('News List')->hasTable('table-bordered'))->toBe(true);
    }
}