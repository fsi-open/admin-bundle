Feature: Pagination
  In order to paginate long news list
  As a developer
  I need to install FSiAdminBundle and configure datasource for newsletter subscribers admin element

  Background:
    Given "subscriber" element datasource max results is set 10

  Scenario: Display pagination
    Given the following admin elements were registered
      | Id         | Class                               |
      | subscriber | FSi\FixturesBundle\Admin\Subscriber |
    And translations are enabled in application
    And there are 20 "subscribers"
    And I am on the "Subscribers list" page
    Then I should see pagination with following buttons
      | Button   | Active | Current |
      | first    | false  | false   |
      | previous | false  | false   |
      | 1        | true   | true    |
      | 2        | true   | false   |
      | next     | true   | false   |
      | last     | true   | false   |

  Scenario: Pagination is not visible when max results is bigger than elements count
    Given there are 8 "subscribers"
    And I am on the "Subscribers list" page
    Then I should not see pagination on page "Subscribers list"

  Scenario: Change current page
    Given there are 20 "subscribers"
    And I am on the "Subscribers list" page
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
