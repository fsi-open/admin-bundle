Feature: Sorting elements at dependent list
  In order to allow sorting elements at dependent list
  As a developer
  I need to install FSiAdminBundle and configure parent admin element and child admin element

  Background:
    Given there is 1 "category"

  Scenario: Display sort links in column header where column name is equal to field name and sortable field option is true
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should see list with following columns
      | Column name   | Sortable |
      | Batch         | false    |
      | Title         | false    |
      | Created at    | true     |
      | Visible       | false    |
      | Creator email | true     |

  Scenario: Change list sorting to asc
    Given I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort asc" button in "Created at" column header
    Then I should be on the "Category news list" page
    And "Sort asc" button in "Created at" column header should be disabled
    And  "Sort desc" button in "Created at" column header should be active

  Scenario: Change list sorting to desc
    Given I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort desc" button in "Created at" column header
    Then I should be on the "Category news list" page
    And "Sort desc" button in "Created at" column header should be disabled
    And  "Sort asc" button in "Created at" column header should be active
