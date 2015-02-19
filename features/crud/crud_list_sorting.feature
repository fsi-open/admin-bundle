Feature: Sorting elements at list
  In order to allow sorting elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for news admin element

  Scenario: Display sort links in column header where column name is equal to field name and sortable field option is true
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And translations are enabled in application
    And I am on the "News list" page
    Then I should see list with following columns
      | Column name   | Sortable |
      | Batch         | false    |
      | Title         | false    |
      | Created at    | true     |
      | Visible       | false    |
      | Creator email | true     |

  Scenario: Change list sorting to asc
    Given I am on the "News list" page
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort asc" button in "Created at" column header
    Then "Sort asc" button in "Created at" column header should be disabled
    And  "Sort desc" button in "Created at" column header should be active

  Scenario: Change list sorting to desc
    Given I am on the "News list" page
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort desc" button in "Created at" column header
    Then "Sort desc" button in "Created at" column header should be disabled
    And  "Sort asc" button in "Created at" column header should be active