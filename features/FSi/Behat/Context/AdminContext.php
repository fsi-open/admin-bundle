<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
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
        $this->useContext('data', new DataContext());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
     * @Given /^following translations are available$/
     */
    public function followingTranslationsAreAvailable(TableNode $table)
    {
        foreach ($table->getHash() as $translationRow) {
            expect($this->kernel->getContainer()->get('translator')->trans($translationRow['Key']))
                ->toBe($translationRow['Translation']);
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

        foreach ($table->getHash() as $elementRow) {
            expect($page->hasMenuElement(
                $elementRow['Element name'],
                empty($elementRow['Element group']) ? null : $elementRow['Element group'])
            )->toBe(true);
        }
    }
}