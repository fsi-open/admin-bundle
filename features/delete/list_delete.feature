Feature: Deleting existing object
  In order to allow deleting existing news
  As a developer
  I need to install FSiAdminBundle and configure delete admin element for newsletter subscribers

  Background:
    Given the following services were registered
      | Id                                      | Class                                     | Tag           |
      | fixtures_bundle.admin.subscriber        | FSi\FixturesBundle\Admin\Subscriber       | admin.element |
      | fixtures_bundle.admin.subscriber_delete | FSi\FixturesBundle\Admin\SubscriberDelete | admin.element |
    And there are 3 subscribers in database

  Scenario: Display delete action
    Given I am on the "Subscribers list" page
    And translations are enabled in application
    Then I should see actions dropdown with following options
      | Option        |
      | Select action |
      | Delete        |
    And I should see confirmation button "Ok"

  @javascript
  Scenario: Delete single subscriber
    Given I am on the "Subscribers list" page
    When I press checkbox in first column in first row
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to "Subscribers list" page
    And there should be 2 subscribers in database

  @javascript
  Scenario: Delete all elements from page
    Given I am on the "Subscribers list" page
    When I press checkbox in first column header
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to "Subscribers list" page
    And there should not be any subscribers in database