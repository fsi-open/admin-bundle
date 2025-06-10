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
use Behat\Mink\Session;
use Doctrine\ORM\EntityManagerInterface;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Bundle\AdminBundle\Behat\Page\Page;
use RuntimeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminContext extends AbstractContext
{
    private ManagerInterface $manager;
    private TranslatorInterface $translator;

    public function __construct(
        Session $session,
        MinkParameters $minkParameters,
        EntityManagerInterface $entityManager,
        ManagerInterface $manager,
        TranslatorInterface $translator
    ) {
        parent::__construct($session, $minkParameters, $entityManager);

        $this->manager = $manager;
        $this->translator = $translator;
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
            Assertion::true($this->manager->hasElement($id));
            Assertion::isInstanceOf($this->manager->getElement($id), $class);
            if (true === array_key_exists('Parent', $serviceRow) && '' !== $serviceRow['Parent']) {
                Assertion::same($this->manager->getElement($id)->getParentId(), $serviceRow['Parent']);
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
            Assertion::true($adminElement->hasOption($option));
            Assertion::eq($adminElement->getOption($option), $value);
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
            Assertion::true($this->manager->hasElement($id));
            Assertion::same($this->manager->getElement($id)->getName(), $name);
        }
    }

    /**
     * @Then /^I should see "([^"]*)" title at top bar$/
     */
    public function iShouldSeeTitleAtTopBar($navbarBrandText): void
    {
        Assertion::eq($this->getPage(AdminPanel::class)->getNavbarBrandText(), $navbarBrandText);
    }

    /**
     * @Given I should see :page page header :headerContent
     */
    public function iShouldSeePageHeader(Page $page, $headerContent): void
    {
        Assertion::eq($page->getHeader(), $headerContent);
    }

    /**
     * @Given /^translations are enabled in application$/
     */
    public function translationsAreEnabledInApplication(): void
    {
        Assertion::isInstanceOf($this->translator, TranslatorInterface::class);
    }

    /**
     * @Then /^I should see language dropdown button in navigation bar with text "([^"]*)"$/
     * @Then /^I should see language dropdown button with text "([^"]*)"$/
     */
    public function iShouldSeeLanguageDropdownButtonInNavigationBarWithText($button): void
    {
        Assertion::true($this->getPage(AdminPanel::class)->getLanguageDropdown()->hasLink($button));
    }

    /**
     * @Given /^language dropdown button should have following links$/
     */
    public function languageDropdownButtonShouldHaveFollowingLinks(TableNode $dropdownLinks): void
    {
        $links = $this->getPage(AdminPanel::class)->getLanguageDropdownOptions();

        foreach ($dropdownLinks->getHash() as $link) {
            Assertion::inArray($link['Link'], $links);
        }
    }

    /**
     * @When /^I click "([^"]*)" link from language dropdown button$/
     */
    public function iClickLinkFromLanguageDropdownButton($link): void
    {
        $this->getPage(AdminPanel::class)->getLanguageDropdown()->clickLink($link);
    }

    /**
     * @Transform /^"([^"]*)" element/
     */
    public function transformListNameToAdminElement(string $id): Element
    {
        return $this->manager->getElement($id);
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

        throw new RuntimeException("Cannot cast \"{$word}\" to int");
    }
}
