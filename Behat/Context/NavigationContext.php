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
use Exception;
use FSi\Bundle\AdminBundle\Behat\Element\Pagination;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Bundle\AdminBundle\Behat\Page\CategoryList;
use FSi\Bundle\AdminBundle\Behat\Page\CategoryNewsCreate;
use FSi\Bundle\AdminBundle\Behat\Page\CategoryNewsEdit;
use FSi\Bundle\AdminBundle\Behat\Page\CategoryNewsList;
use FSi\Bundle\AdminBundle\Behat\Page\CustomNewsEdit;
use FSi\Bundle\AdminBundle\Behat\Page\CustomNewsList;
use FSi\Bundle\AdminBundle\Behat\Page\CustomSubscribersList;
use FSi\Bundle\AdminBundle\Behat\Page\DefaultPage;
use FSi\Bundle\AdminBundle\Behat\Page\DTOForm;
use FSi\Bundle\AdminBundle\Behat\Page\NewsDisplay;
use FSi\Bundle\AdminBundle\Behat\Page\HomePageEdit;
use FSi\Bundle\AdminBundle\Behat\Page\NewsCreate;
use FSi\Bundle\AdminBundle\Behat\Page\NewsEdit;
use FSi\Bundle\AdminBundle\Behat\Page\NewsList;
use FSi\Bundle\AdminBundle\Behat\Page\Page;
use FSi\Bundle\AdminBundle\Behat\Page\PersonAddForm;
use FSi\Bundle\AdminBundle\Behat\Page\PersonEditForm;
use FSi\Bundle\AdminBundle\Behat\Page\PersonList;
use FSi\Bundle\AdminBundle\Behat\Page\SubscriberEdit;
use FSi\Bundle\AdminBundle\Behat\Page\SubscriberForm;
use FSi\Bundle\AdminBundle\Behat\Page\SubscribersList;
use InvalidArgumentException;

class NavigationContext extends AbstractContext
{
    /**
     * @transform :page
     */
    public function transformToPageObject($pageName)
    {
        switch ($pageName) {
            case 'News list':
                return $this->getPage(NewsList::class);
            case 'Admin panel':
                return $this->getPage(AdminPanel::class);
            case 'Custom news edit':
                return $this->getPage(CustomNewsEdit::class);
            case 'Custom news list':
                return $this->getPage(CustomNewsList::class);
            case 'Custom subscribers list':
                return $this->getPage(CustomSubscribersList::class);
            case 'DTO Form':
                return $this->getPage(DTOForm::class);
            case 'Home page edit':
                return $this->getPage(HomePageEdit::class);
            case 'News create':
                return $this->getPage(NewsCreate::class);
            case 'News display':
                return $this->getPage(NewsDisplay::class);
            case 'News edit':
                return $this->getPage(NewsEdit::class);
            case 'Category list':
                return $this->getPage(CategoryList::class);
            case 'Category news list':
                return $this->getPage(CategoryNewsList::class);
            case 'Category news edit':
                return $this->getPage(CategoryNewsEdit::class);
            case 'Category news create':
                return $this->getPage(CategoryNewsCreate::class);
            case 'Person add form':
                return $this->getPage(PersonAddForm::class);
            case 'Person edit form':
                return $this->getPage(PersonEditForm::class);
            case 'Person list':
                return $this->getPage(PersonList::class);
            case 'Subscriber edit':
                return $this->getPage(SubscriberEdit::class);
            case 'Subscriber form':
                return $this->getPage(SubscriberForm::class);
            case 'Subscribers list':
                return $this->getPage(SubscribersList::class);
            default:
                throw new InvalidArgumentException(
                    sprintf('Could not transform "%s" to any page object', $pageName)
                );
        }
    }

    /**
     * @When /^I follow "([^"]*)" url from top bar$/
     * @Given /^I follow "([^"]*)" menu element$/
     */
    public function iFollowUrlFromTopBar($menuElement): void
    {
        $this->getPage(AdminPanel::class)->getMenu()->clickLink($menuElement);
    }

    /**
     * @Then /^menu with following elements should be visible at the top of the page$/
     */
    public function menuWithFollowingElementsShouldBeVisibleAtTheTopOfThePage(TableNode $table): void
    {
        expect($this->getPage(AdminPanel::class)->getMenuElementsCount())->toBe(count($table->getHash()));

        foreach ($table->getHash() as $elementRow) {
            expect($this->getPage(AdminPanel::class)->hasMenuElement(
                $elementRow['Element name'],
                empty($elementRow['Element group']) ? null : $elementRow['Element group']
            ))->toBe(true);
        }
    }

    /**
     * @Then :link link in the top bar should be highlighted
     */
    public function linkInTheTopBarShouldBeHighlighted($link): void
    {
        $linkNode = $this->getPage(AdminPanel::class)->getMenu()->findLink($link);

        expect($linkNode->getParent()->hasClass('active'))->toBe(true);
    }

    /**
     * @Given I am on the :page page
     */
    public function iAmOnThePage(Page $page): void
    {
        $page->open();
    }

    /**
     * @Given I am on the :page page with id :id
     */
    public function iAmOnThePageWithId(Page $page, $id): void
    {
        $page->open(['id' => $id]);
    }

    /**
     * @Given I should be on the :page page
     * @Given I should be redirected to :page page
     */
    public function iShouldBeOnThePage(Page $page): void
    {
        expect($page->isOpen())->toBe(true);
    }

    /**
     * @Given I try to open the :page page
     */
    public function iTryToOpenPage(Page $page): void
    {
        $page->openWithoutVerifying();
    }

    /**
     * @Given /^I press "New element" link$/
     */
    public function iPressNewElementLink(): void
    {
        $this->getSession()->getPage()->find('css', 'a#create-element')->click();
    }

    /**
     * @When /^I press "([^"]*)" button at pagination$/
     */
    public function iPressButtonAtPagination(string $button): void
    {
        $this->getElement(Pagination::class)->clickLink($button);
    }

    /**
     * @Then /^I should see pagination with following buttons$/
     */
    public function iShouldSeePaginationWithFollowingButtons(TableNode $table): void
    {
        $pagination = $this->getElement(Pagination::class);

        foreach ($table->getHash() as $buttonRow) {
            expect($pagination->hasLink($buttonRow['Button']))->toBe(true);

            if ('true' === $buttonRow['Active']) {
                expect($pagination->isDisabled($buttonRow['Button']))->toBe(false);
            } else {
                expect($pagination->isDisabled($buttonRow['Button']))->toBe(true);
            }

            if ('true' === $buttonRow['Current']) {
                expect($pagination->isCurrentPage($buttonRow['Button']))->toBe(true);
            } else {
                expect($pagination->isCurrentPage($buttonRow['Button']))->toBe(false);
            }
        }
    }

    /**
     * @Then page :page should display OK status
     */
    public function pageShouldDisplayOKStatus(Page $page): void
    {
        $this->expectPageStatus($page, 200);
    }

    /**
     * @Then page :page should display not found exception
     */
    public function pageShouldDisplayNotFoundException(Page $page): void
    {
        $this->expectPageStatus($page, 404);
    }

    /**
     * @Then page :page should throw an error exception
     */
    public function pageShouldThrowAnErrorExceptionException(Page $page): void
    {
        $this->expectPageStatus($page, 500);
    }

    private function expectPageStatus(Page $page, int $expectedStatus): void
    {
        $status = $page->getStatusCode();
        if ($status !== $expectedStatus) {
            throw new Exception(sprintf(
                'Invalid status code "%s", expected "%s".',
                $status,
                $expectedStatus
            ));
        }
    }
}
