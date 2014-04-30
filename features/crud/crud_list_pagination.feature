Feature: Pagination
  In order to paginate long news list
  As a developer
  I need to install FSiAdminBundle and configure datasource for news admin element

  Background:
    Given "news" element datasource max results is set 10

  Scenario: Display pagination
    Given the following services were registered
      | Id                         | Class                         | Tag           |
      | fixtures_bundle.admin.news | FSi\FixturesBundle\Admin\News | admin.element |
    And translations are enabled in application
    And there are 20 news in database
    And I am on the "News list" page
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | false  | false   |
      | previous | false  | false   |
      | 1        | true   | true    |
      | 2        | true   | false   |
      | next     | true   | false   |
      | last     | true   | false   |

  Scenario: Pagination is not visible when max results is bigger than elements count
    Given there are 8 news in database
    And I am on the "News list" page
    Then I should not see pagination

  Scenario: Change current page
    Given there are 20 news in database
    And I am on the "News list" page
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | false  | false   |
      | previous | false  | false   |
      | 1        | true   | true    |
      | 2        | true   | false   |
      | next     | true   | false   |
      | last     | true   | false   |
    When I press "2" button at pagination
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | true   | false   |
      | previous | true   | false   |
      | 1        | true   | false   |
      | 2        | true   | true    |
      | next     | false  | false   |
      | last     | false  | false   |