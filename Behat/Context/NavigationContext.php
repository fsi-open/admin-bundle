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
     * @var DefaultPage
     */
    private $defaultPage;

    /**
     * @var AdminPanel
     */
    private $adminPanelPage;

    /**
     * @var CustomNewsEdit
     */
    private $customNewsEditPage;

    /**
     * @var CustomNewsList
     */
    private $customNewsListPage;

    /**
     * @var CustomSubscribersList
     */
    private $customSubscribersListPage;

    /**
     * @var DTOForm
     */
    private $dtoFormPage;

    /**
     * @var HomePageEdit
     */
    private $homePageEditPage;

    /**
     * @var NewsCreate
     */
    private $newsCreatePage;

    /**
     * @var NewsDisplay
     */
    private $newsDisplayPage;

    /**
     * @var NewsEdit
     */
    private $newsEditPage;

    /**
     * @var CategoryList
     */
    private $categoryListPage;

    /**
     * @var CategoryNewsList
     */
    private $categoryNewsListPage;

    /**
     * @var CategoryNewsCreate
     */
    private $categoryNewsCreatePage;

    /**
     * @var CategoryNewsEdit
     */
    private $categoryNewsEditPage;

    /**
     * @va NewsList
     */
    private $newsListPage;

    /**
     * @var PersonAddForm
     */
    private $personAddFormPage;

    /**
     * @var PersonEditForm
     */
    private $personEditFormPage;

    /**
     * @var PersonList
     */
    private $personListPage;

    /**
     * @var SubscriberEdit
     */
    private $subscriberEditPage;

    /**
     * @var SubscriberForm
     */
    private $subscriberFormPage;

    /**
     * @var SubscribersList
     */
    private $subscribersListPage;

    public function __construct(
        DefaultPage $defaultPage,
        AdminPanel $adminPanelPage,
        CustomNewsEdit $customNewsEditPage,
        CustomNewsList $customNewsListPage,
        CustomSubscribersList $customSubscribersListPage,
        DTOForm $dtoFormPage,
        HomePageEdit $homePageEditPage,
        NewsCreate $newsCreatePage,
        NewsDisplay $newsDisplayPage,
        NewsEdit $newsEditPage,
        NewsList $newsListPage,
        CategoryList $categoryListPage,
        CategoryNewsList $categoryNewsListPage,
        CategoryNewsCreate $categoryNewsCreatePage,
        CategoryNewsEdit $categoryNewsEditPage,
        PersonAddForm $personAddFormPage,
        PersonEditForm $personEditFormPage,
        PersonList $personListPage,
        SubscriberEdit $subscriberEditPage,
        SubscriberForm $subscriberFormPage,
        SubscribersList $subscribersListPage
    ) {
        $this->defaultPage = $defaultPage;
        $this->adminPanelPage = $adminPanelPage;
        $this->customNewsEditPage = $customNewsEditPage;
        $this->customNewsListPage = $customNewsListPage;
        $this->customSubscribersListPage = $customSubscribersListPage;
        $this->dtoFormPage = $dtoFormPage;
        $this->homePageEditPage = $homePageEditPage;
        $this->newsCreatePage = $newsCreatePage;
        $this->newsDisplayPage = $newsDisplayPage;
        $this->newsEditPage = $newsEditPage;
        $this->newsListPage = $newsListPage;
        $this->categoryListPage = $categoryListPage;
        $this->categoryNewsListPage = $categoryNewsListPage;
        $this->categoryNewsCreatePage = $categoryNewsCreatePage;
        $this->categoryNewsEditPage = $categoryNewsEditPage;
        $this->personAddFormPage = $personAddFormPage;
        $this->personEditFormPage = $personEditFormPage;
        $this->personListPage = $personListPage;
        $this->subscriberEditPage = $subscriberEditPage;
        $this->subscriberFormPage = $subscriberFormPage;
        $this->subscribersListPage = $subscribersListPage;
    }

    /**
     * @transform :page
     */
    public function transformToPageObject($pageName)
    {
        switch ($pageName) {
            case 'News list':
                return $this->newsListPage;
            case 'Admin panel':
                return $this->adminPanelPage;
            case 'Custom news edit':
                return $this->customNewsEditPage;
            case 'Custom news list':
                return $this->customNewsListPage;
            case 'Custom subscribers list':
                return $this->customSubscribersListPage;
            case 'DTO Form':
                return $this->dtoFormPage;
            case 'Home page edit':
                return $this->homePageEditPage;
            case 'News create':
                return $this->newsCreatePage;
            case 'News display':
                return $this->newsDisplayPage;
            case 'News edit':
                return $this->newsEditPage;
            case 'Category list':
                return $this->categoryListPage;
            case 'Category news list':
                return $this->categoryNewsListPage;
            case 'Category news edit':
                return $this->categoryNewsEditPage;
            case 'Category news create':
                return $this->categoryNewsCreatePage;
            case 'Person add form':
                return $this->personAddFormPage;
            case 'Person edit form':
                return $this->personEditFormPage;
            case 'Person list':
                return $this->personListPage;
            case 'Subscriber edit':
                return $this->subscriberEditPage;
            case 'Subscriber form':
                return $this->subscriberFormPage;
            case 'Subscribers list':
                return $this->subscribersListPage;
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
        $this->adminPanelPage->getMenu()->clickLink($menuElement);
    }

    /**
     * @Then /^menu with following elements should be visible at the top of the page$/
     */
    public function menuWithFollowingElementsShouldBeVisibleAtTheTopOfThePage(TableNode $table): void
    {
        expect($this->adminPanelPage->getMenuElementsCount())->toBe(count($table->getHash()));

        foreach ($table->getHash() as $elementRow) {
            expect($this->adminPanelPage->hasMenuElement(
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
        $linkNode = $this->adminPanelPage->getMenu()->findLink($link);

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
        $this->defaultPage->getElement('New Element Link')->click();
    }

    /**
     * @When /^I press "([^"]*)" button at pagination$/
     */
    public function iPressButtonAtPagination($button): void
    {
        $this->defaultPage->getElement('Pagination')->clickLink($button);
    }

    /**
     * @Then /^I should see pagination with following buttons$/
     */
    public function iShouldSeePaginationWithFollowingButtons(TableNode $table): void
    {
        $pagination = $this->defaultPage->getElement('Pagination');

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
