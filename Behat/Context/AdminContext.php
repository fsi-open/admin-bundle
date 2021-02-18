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
use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Admin\Element;
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
     * @BeforeScenario
     */
    public function resizeWindow(): void
    {
        if (true === $this->isSeleniumDriverUsed()) {
            if (false === $this->getSession()->isStarted()) {
                $this->getSession()->start();
            }

            $this->getSession()->resizeWindow(1280, 1024);
        }
    }

    /**
     * @Given /^the following admin elements were registered$/
     */
    public function theFollowingAdminElementsWereRegistered(TableNode $table): void
    {
        foreach ($table->getHash() as $serviceRow) {
            $id = $serviceRow['Id'];
            $class = $serviceRow['Class'];
            expect($this->getAdminManager()->hasElement($id))->toBe(true);
            expect($this->getAdminManager()->getElement($id))->toBeAnInstanceOf($class);
            if (true === array_key_exists('Parent', $serviceRow) && '' !== $serviceRow['Parent']) {
                expect($this->getAdminManager()->getElement($id)->getParentId())->toBe($serviceRow['Parent']);
            }
        }
    }

    /**
     * @Given /^("[^"]*" element) have following options defined$/
     */
    public function elementHaveFollowingOptionsDefined(AbstractElement $adminElement, TableNode $options): void
    {
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
    public function thereAreFollowingAdminElementsAvailable(TableNode $table): void
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
    public function iShouldSeeTitleAtTopBar($navbarBrandText): void
    {
        expect($this->adminPanelPage->getNavbarBrandText())->toBe($navbarBrandText);
    }

    /**
     * @Given I should see :page page header :headerContent
     */
    public function iShouldSeePageHeader(Page $page, $headerContent): void
    {
        expect($page->getHeader())->toBe($headerContent);
    }

    /**
     * @Given /^translations are enabled in application$/
     */
    public function translationsAreEnabledInApplication(): void
    {
        $translator = $this->getContainer()->get('translator');
        expect($translator)->toBeAnInstanceOf('Symfony\Component\Translation\TranslatorInterface');
    }

    /**
     * @Then /^I should see language dropdown button in navigation bar with text "([^"]*)"$/
     * @Then /^I should see language dropdown button with text "([^"]*)"$/
     */
    public function iShouldSeeLanguageDropdownButtonInNavigationBarWithText($button): void
    {
        expect($this->adminPanelPage->getLanguageDropdown()->hasLink($button))->toBe(true);
    }

    /**
     * @Given /^language dropdown button should have following links$/
     */
    public function languageDropdownButtonShouldHaveFollowingLinks(TableNode $dropdownLinks): void
    {
        $links = $this->adminPanelPage->getLanguageDropdownOptions();

        foreach ($dropdownLinks->getHash() as $link) {
            expect($links)->toContain($link['Link']);
        }
    }

    /**
     * @When /^I click "([^"]*)" link from language dropdown button$/
     */
    public function iClickLinkFromLanguageDropdownButton($link): void
    {
        $this->adminPanelPage->getLanguageDropdown()->clickLink($link);
    }

    /**
     * @Transform /^"([^"]*)" element/
     */
    public function transformListNameToAdminElement(string $id): Element
    {
        return $this->getAdminManager()->getElement($id);
    }

    /**
     * @Transform /^(\d+)/
     */
    public function castStringToNumber(string $number): int
    {
        return (int) $number;
    }

    /**
     * @Transform /^first|second|third$/
     */
    public function castWordToNumber(string $word): int
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

    private function getAdminManager(): ManagerInterface
    {
        return $this->getContainer()->get(sprintf('test.%s', ManagerInterface::class));
    }
}
