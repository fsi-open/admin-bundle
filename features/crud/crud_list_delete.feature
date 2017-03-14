Feature: Deleting existing object
  In order to allow deleting existing news
  As a developer
  I need to install FSiAdminBundle and configure news admin element

  Background:
    Given the following news exist in database
      | Title  |
      | News 1 |
      | News 2 |
      | News 3 |

  Scenario: Display delete action
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And I am on the "News list" page
    And translations are enabled in application
    Then I should see actions dropdown with following options
      | Option        |
      | Select action |
      | Delete        |
    And I should see confirmation button "Ok"


  Scenario: Delete single news
    Given I am on the "News list" page
    When I press checkbox in first column in first row
    And I perform the batch action "Delete"
    Then I should be redirected to "News list" page
    And "news" with "title" "News 1" should not exist in database anymore
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Delete all elements from page
    Given I am on the "News list" page
    When I press checkbox in first column header
    And I perform the batch action "Delete"
    Then I should be redirected to "News list" page
    And there should not be any "news" present in the database
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Perform deletion without selecting any elements
    Given I am on the "News list" page
    When I perform the batch action "Delete"
    Then I should be redirected to "News list" page
    And there should be 3 elements at list
    And I should see a warning message saying:
    """
    Cannot perform batch action without selecting any elements.
    """
