<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Exception;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;
use FSi\Bundle\AdminBundle\Behat\Page\CustomNewsEdit;
use FSi\Bundle\AdminBundle\Behat\Page\CustomNewsList;
use FSi\Bundle\AdminBundle\Behat\Page\CustomSubscribersList;
use FSi\Bundle\AdminBundle\Behat\Page\DefaultPage;
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
        HomePageEdit $homePageEditPage,
        NewsCreate $newsCreatePage,
        NewsDisplay $newsDisplayPage,
        NewsEdit $newsEditPage,
        NewsList $newsListPage,
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
        $this->homePageEditPage = $homePageEditPage;
        $this->newsCreatePage = $newsCreatePage;
        $this->newsDisplayPage = $newsDisplayPage;
        $this->newsEditPage = $newsEditPage;
        $this->newsListPage = $newsListPage;
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
            case "News list":
                return $this->newsListPage;
            case "Admin panel":
                return $this->adminPanelPage;
            case "Custom news edit":
                return $this->customNewsEditPage;
            case "Custom news list":
                return $this->customNewsListPage;
            case "Custom subscribers list":
                return $this->customSubscribersListPage;
            case "Home page edit":
                return $this->homePageEditPage;
            case "News create":
                return $this->newsCreatePage;
            case "News display":
                return $this->newsDisplayPage;
            case "News edit":
                return $this->newsEditPage;
            case "News list":
                return $this->newsListPage;
            case "Person add form":
                return $this->personAddFormPage;
            case "Person edit form":
                return $this->personEditFormPage;
            case "Person list":
                return $this->personListPage;
            case "Subscriber edit":
                return $this->subscriberEditPage;
            case "Subscriber form":
                return $this->subscriberFormPage;
            case "Subscribers list":
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
    public function iFollowUrlFromTopBar($menuElement)
    {
        $this->adminPanelPage->getMenu()->clickLink($menuElement);
    }

    /**
     * @Then /^menu with following elements should be visible at the top of the page$/
     */
    public function menuWithFollowingElementsShouldBeVisibleAtTheTopOfThePage(TableNode $table)
    {
        expect($this->adminPanelPage->getMenuElementsCount())->toBe(count($table->getHash()));

        foreach ($table->getHash() as $elementRow) {
            expect($this->adminPanelPage->hasMenuElement(
                $elementRow['Element name'],
                empty($elementRow['Element group']) ? null : $elementRow['Element group'])
            )->toBe(true);
        }
    }

    /**
     * @Given I am on the :page page
     */
    public function iAmOnThePage(Page $page)
    {
        $page->open();
    }

    /**
     * @Given I am on the :page page with id :id
     */
    public function iAmOnThePageWithId(Page $page, $id)
    {
        $page->open(['id' => $id]);
    }

    /**
     * @Given I should be on the :page page
     * @Given I should be redirected to :page page
     */
    public function iShouldBeOnThePage(Page $page)
    {
        expect($page->isOpen())->toBe(true);
    }

    /**
     * @Given I try to open the :page page
     */
    public function iTryToOpenPage(Page $page)
    {
        $page->openWithoutVerifying();
    }

    /**
     * @Given /^I press "New element" link$/
     */
    public function iPressNewElementLink()
    {
        $this->defaultPage->getElement('New Element Link')->click();
    }

    /**
     * @When /^I press "([^"]*)" button at pagination$/
     */
    public function iPressButtonAtPagination($button)
    {
        $this->defaultPage->getElement('Pagination')->clickLink($button);
    }

    /**
     * @Then /^I should see pagination with following buttons$/
     */
    public function iShouldSeePaginationWithFollowingButtons(TableNode $table)
    {
        $pagination = $this->defaultPage->getElement('Pagination');

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
     * @Then page :page should display OK status
     */
    public function pageShouldDisplayOKStatus(Page $page)
    {
        $this->expectPageStatus($page, 200);
    }

    /**
     * @Then page :page should display not found exception
     */
    public function pageShouldDisplayNotFoundException(Page $page)
    {
        $this->expectPageStatus($page, 404);
    }

    /**
     * @Then page :page should throw an error exception
     */
    public function pageShouldThrowAnErrorExceptionException(Page $page)
    {
        $this->expectPageStatus($page, 500);
    }

    /**
     * @param Page $page
     * @param int $expectedStatus
     * @throws Exception
     */
    private function expectPageStatus(Page $page, $expectedStatus)
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
