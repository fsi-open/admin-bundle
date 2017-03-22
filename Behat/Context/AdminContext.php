<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Bundle\AdminBundle\Behat\Page\Page;

class AdminContext extends AbstractContext
{
    /**
     * @var AdminPanel
     */
    private $adminPanelPage;

    public function __construct(AdminPanel $adminPanelPage)
    {
        $this->adminPanelPage = $adminPanelPage;
    }

    /**
     * @Given /^the following admin elements were registered$/
     */
    public function theFollowingAdminElementsWereRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $serviceRow) {
            $id = $serviceRow['Id'];
            $class = $serviceRow['Class'];
            expect($this->getAdminManager()->hasElement($id))->toBe(true);
            expect($this->getAdminManager()->getElement($id))->toBeAnInstanceOf($class);
        }
    }

    /**
     * @Given /^("[^"]*" element) have following options defined$/
     */
    public function elementHaveFollowingOptionsDefined(
        AbstractElement $adminElement,
        TableNode $options
    ) {
        foreach ($options->getHash() as $optionRow) {
            $option = $optionRow['Option'];
            $value = $optionRow['Value'];
            expect($adminElement->hasOption($option))->toBe(true);
            expect($adminElement->getOption($option))->toBe($value);
        }
    }

    /**
     * @Given /^there are following admin elements available$/
     */
    public function thereAreFollowingAdminElementsAvailable(TableNode $table)
    {
        foreach ($table->getHash() as $elementRow) {
            $id = $elementRow['Id'];
            $name = $elementRow['Name'];
            expect($this->getAdminManager()->hasElement($id))->toBe(true);
            expect($this->getAdminManager()->getElement($id)->getName())->toBe($name);
        }
    }

    /**
     * @Then /^I should see "([^"]*)" title at top bar$/
     */
    public function iShouldSeeTitleAtTopBar($navbarBrandText)
    {
        expect($this->adminPanelPage->getNavbarBrandText())->toBe($navbarBrandText);
    }

    /**
     * @Given I should see :page page header :headerContent
     */
    public function iShouldSeePageHeader(Page $page, $headerContent)
    {
        expect($page->getHeader())->toBe($headerContent);
    }

    /**
     * @Given /^translations are enabled in application$/
     */
    public function translationsAreEnabledInApplication()
    {
        $translator = $this->getContainer()->get('translator');
        expect($translator)->toBeAnInstanceOf('Symfony\Component\Translation\TranslatorInterface');
    }

    /**
     * @Then /^I should see language dropdown button in navigation bar with text "([^"]*)"$/
     * @Then /^I should see language dropdown button with text "([^"]*)"$/
     */
    public function iShouldSeeLanguageDropdownButtonInNavigationBarWithText($button)
    {
        expect($this->adminPanelPage->getLanguageDropdown()->hasLink($button))->toBe(true);
    }

    /**
     * @Given /^language dropdown button should have following links$/
     */
    public function languageDropdownButtonShouldHaveFollowingLinks(TableNode $dropdownLinks)
    {
        $links = $this->adminPanelPage->getLanguageDropdownOptions();

        foreach ($dropdownLinks->getHash() as $link) {
            expect($links)->toContain($link['Link']);
        }
    }

    /**
     * @When /^I click "([^"]*)" link from language dropdown button$/
     */
    public function iClickLinkFromLanguageDropdownButton($link)
    {
        $this->adminPanelPage->getLanguageDropdown()->clickLink($link);
    }

    /**
     * @Transform /^"([^"]*)" element/
     */
    public function transformListNameToAdminElement($id)
    {
        return $this->getAdminManager()->getElement($id);
    }

    /**
     * @Transform /^(\d+)/
     */
    public function castStringToNumber($number)
    {
        return (int) $number;
    }

    /**
     * @Transform /^first|second|third$/
     */
    public function castWordToNumber($word)
    {
        switch ($word) {
            case 'first':
                return 1;
            case 'second':
                return 2;
            case 'third':
                return 3;
        }
    }

    /**
     * @return ManagerInterface
     */
    private function getAdminManager()
    {
        return $this->getContainer()->get('admin.manager');
    }
}
