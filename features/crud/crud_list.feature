Feature: List of elements
  In order to generate list of elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure datagrid for news admin element


  Scenario: Accessing news list from admin panel main page
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And translations are enabled in application
    And I am on the "Admin panel" page
    When I follow "News" url from top bar
    Then I should see list with following columns
      | Column name   |
      | Batch         |
      | Title         |
      | Date          |
      | Created at    |
      | Visible       |
      | Creator email |
    And I should see "News list" page header "List of elements"
