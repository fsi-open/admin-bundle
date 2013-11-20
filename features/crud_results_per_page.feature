Feature: Changing results per page at list page
  In order to generate list of elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure datagrid and datasource for news admin element

  Scenario: Change list sorting to asc
    And the following services were registered
      | Id                     | Class                                    | Tag           | Tag alias |
      | demo_bundle.admin.news | FSi\Behat\Fixtures\DemoBundle\Admin\News | admin.element |           |
    And "news" element datasource max results is set 10

  Scenario: Display list with elements count equal to datasource max results
    Given there are 20 news in database
    And I am on the "News list" page
    Then there should be 10 elements at list

  Scenario: Change elements per page
    Given there are 20 news in database
    And I am on the "News list" page
    Then there should be 10 elements at list
    When I change elements per page to 5
    Then there should be 5 elements at list
    When I change elements per page to 50
    Then there should be 20 elements at list