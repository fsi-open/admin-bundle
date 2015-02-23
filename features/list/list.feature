Feature: List of elements
  In order to generate list of elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure datagrid for newsletter subscribers admin element

  Scenario: Accessing newsletter subscribers list from admin panel main page
    Given the following admin elements were registered
      | Id         | Class                               |
      | subscriber | FSi\FixturesBundle\Admin\Subscriber |
    And translations are enabled in application
    And I am on the "Admin panel" page
    When I follow "Subscribers" url from top bar
    Then I should see list with following columns
      | Column name   |
      | Email         |
      | Active        |
      | Created at    |
    And I should see "Subscribers List" page header "List of elements"
