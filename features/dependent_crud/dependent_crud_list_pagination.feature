Feature: Pagination
  In order to paginate a long dependent list
  As a developer
  I need to install FSiAdminBundle and configure parent admin element and child admin element

  Background:
    Given "category_news" element datasource max results is set 10

  Scenario: Display pagination
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And there is 1 "category"
    And there are 20 "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | false  | false   |
      | previous | false  | false   |
      | 1        | true   | true    |
      | 2        | true   | false   |
      | next     | true   | false   |
      | last     | true   | false   |

  Scenario: Pagination is not visible when max results is bigger than elements count
    Given there is 1 "category"
    And there are 8 "news"
    When I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should not see pagination on page "Category news list"

  Scenario: Change current page
    Given there is 1 "category"
    And there are 20 "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | false  | false   |
      | previous | false  | false   |
      | 1        | true   | true    |
      | 2        | true   | false   |
      | next     | true   | false   |
      | last     | true   | false   |
    When I press "2" button at pagination
    Then I should be on the "Category news list" page
    And I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | true   | false   |
      | previous | true   | false   |
      | 1        | true   | false   |
      | 2        | true   | true    |
      | next     | false  | false   |
      | last     | false  | false   |
