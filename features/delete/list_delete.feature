Feature: Deleting existing object
  In order to allow deleting existing news
  As a developer
  I need to install FSiAdminBundle and configure delete admin element for newsletter subscribers


  Background:
    Given the following admin elements were registered
      | Id                | Class                                     |
      | subscriber        | FSi\FixturesBundle\Admin\Subscriber       |
      | subscriber_delete | FSi\FixturesBundle\Admin\SubscriberDelete |
    And there are 3 "subscribers"


  Scenario: Display delete action
    Given I am on the "Subscribers list" page
    And translations are enabled in application
    Then I should see actions dropdown with following options
      | Option        |
      | Select action |
      | Delete        |
    And I should see confirmation button "Ok"


  Scenario: Delete single subscriber
    Given I am on the "Subscribers list" page
    When I press checkbox in first column in first row
    And I perform the batch action "Delete"
    Then I should be redirected to "Subscribers list" page
    And there should be 2 "subscribers" present in the database
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Delete all elements from page
    Given I am on the "Subscribers list" page
    When I press checkbox in first column header
    And I perform the batch action "Delete"
    Then I should be redirected to "Subscribers list" page
    And there should not be any "subscribers" present in the database
    And I should see a success message saying:
    """
    Operation has been completed successfully.
    """


  Scenario: Deleting object with an element not allowing deletion
    Given there is a "person" with "id" 1 present in the database
    And I am on the "Person list" page
    When I press checkbox in first column in first row
    And I perform the batch action "Delete"
    Then page "Person list" should throw an error exception
    And there should be a "person" with "id" "1" present in the database
