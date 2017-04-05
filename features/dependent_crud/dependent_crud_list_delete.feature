Feature: Deleting existing depndent object
  In order to allow deleting existing news from dependent list
  As a developer
  I need to install FSiAdminBundle and configure news dependent admin element

  Background:
    Given there is a "category" with "title" "events" present in the database
    And the following news exist in database
      | Title  | Category |
      | News 1 | events   |
      | News 2 | events   |
      | News 3 | events   |

  Scenario: Display delete action
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should see actions dropdown with following options
      | Option        |
      | Select action |
      | Delete        |
    And I should see confirmation button "Ok"


  Scenario: Delete single news
    Given I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    When I press checkbox in first column in first row
    And I perform the batch action "Delete"
    Then I should be redirected to "Category news list" page
    And "news" with "title" "News 1" should not exist in database anymore
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Delete all elements from page
    Given I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    When I press checkbox in first column header
    And I perform the batch action "Delete"
    Then I should be redirected to "Category news list" page
    And there should not be any "news" present in the database
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Perform deletion without selecting any elements
    Given I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    When I perform the batch action "Delete"
    Then I should be redirected to "Category news list" page
    And there should be 3 elements at list
    And I should see a warning message saying:
    """
    Cannot perform batch action without selecting any elements.
    """
