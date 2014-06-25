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
    Given the following services were registered
      | Id                         | Class                         | Tag           |
      | fixtures_bundle.admin.news | FSi\FixturesBundle\Admin\News | admin.element |
    And I am on the "News list" page
    And translations are enabled in application
    Then I should see actions dropdown with following options
      | Option        |
      | Select action |
      | Delete        |
    And I should see confirmation button "Ok"

  @javascript
  Scenario: Delete single news
    Given I am on the "News list" page
    When I press checkbox in first column in first row
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 1 from selected elements?
    """
    When I press "Yes"
    Then I should be redirected to "News list" page
    And news "News 1" should not exist in database anymore

  @javascript
  Scenario: Cancel delete single news
    Given I am on the "News list" page
    When I press checkbox in first column in first row
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 1 from selected elements?
    """
    When I press "No"
    Then I should be redirected to "News list" page
    And news "News 1" should exist in database

  @javascript
  Scenario: Delete all elements from page
    Given I am on the "News list" page
    When I press checkbox in first column header
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 3 from selected elements?
    """
    When I press "Yes"
    Then I should be redirected to "News list" page
    And there should not be any news in database

  @javascript
  Scenario: Cancel delete all elements from page
    Given I am on the "News list" page
    When I press checkbox in first column header
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 3 from selected elements?
    """
    When I press "No"
    Then I should be redirected to "News list" page
    And there should be 3 news in database
