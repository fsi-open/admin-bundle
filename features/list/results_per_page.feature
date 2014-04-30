Feature: Changing results per page at list page
  In order to generate list of subscribers in admin panel
  As a developer
  I need to install FSiAdminBundle and configure datagrid and datasource for newsletter subscribers admin element

  Background:
    Given "subscriber" element datasource max results is set 10

  Scenario: Display list with elements count equal to datasource max results
    Given the following services were registered
      | Id                               | Class                               | Tag           |
      | fixtures_bundle.admin.subscriber | FSi\FixturesBundle\Admin\Subscriber | admin.element |
    And there are 20 subscribers in database
    And I am on the "Subscribers list" page
    Then there should be 10 elements at list

  Scenario: Change elements per page
    Given there are 20 subscribers in database
    And I am on the "Subscribers list" page
    Then there should be 10 elements at list
    When I change elements per page to 5
    Then there should be 5 elements at list
    When I change elements per page to 50
    Then there should be 20 elements at list
