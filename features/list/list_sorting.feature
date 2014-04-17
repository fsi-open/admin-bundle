Feature: Sorting elements at list
  In order to allow sorting elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for newsletter subscribers admin element

  Scenario: Display sort links in column header where column name is equal to field name and sortable field option is true
    Given the following services were registered
      | Id                               | Class                               | Tag           |
      | fixtures_bundle.admin.subscriber | FSi\FixturesBundle\Admin\Subscriber | admin.element |
    And translations are enabled in application
    And I am on the "Subscribers list" page
    Then I should see list with following columns
      | Column name   | Sortable |
      | Email         | true     |
      | Active        | false    |
      | Created at    | true     |

  Scenario: Change list sorting to asc
    Given I am on the "Subscribers list" page
    Then both sorting buttons in column header "Email" should be active
    When I press "Sort asc" button in "Email" column header
    Then "Sort asc" button in "Email" column header should be disabled
    And  "Sort desc" button in "Email" column header should be active

  Scenario: Change list sorting to desc
    Given I am on the "Subscribers list" page
    Then both sorting buttons in column header "Email" should be active
    When I press "Sort desc" button in "Email" column header
    Then "Sort desc" button in "Email" column header should be disabled
    And  "Sort asc" button in "Email" column header should be active
