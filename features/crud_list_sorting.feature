Feature: Sorting elements at list
  In order to allow sorting elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for news admin element

  Background:
    Given I am on the "Admin panel" page
    And the following services were registered
      | Id                     | Class                                    | Tag           | Tag alias |
      | demo_bundle.admin.news | FSi\Behat\Fixtures\DemoBundle\Admin\News | admin.element |           |
    And following columns should be added to "news" element datagrid
      | Column name   | Column type |
      | title         | text        |
      | created_at    | datetime    |
      | creator_email | text        |
    And following fields should be added to "news" element datasource
      | Field name    | Field type | Field comparison |
      | title         | text       | like             |
      | created_at    | date       | between          |
      | creator_email | text       | like             |
    And following options should be defined for "news" element datasource fields
      | Field name    | Option      | Value |
      | title         | sortable    | false |
      | creator_email | sortable    | true  |
      | visible       | sortable    | false |
      | created_at    | sortable    | true  |

  Scenario: Display sort links in column header where column name is equal to field name and sortable field option is true
    When I follow "News" menu element
    Then I should see list with following columns
      | Column name   | Sortable |
      | Batch         | false    |
      | Title         | false    |
      | Created at    | true     |
      | Visible       | false    |
      | Creator email | true     |

  Scenario: Change list sorting to asc
    When I follow "News" menu element
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort asc" button in "Created at" column header
    Then "Sort asc" button in "Created at" column header should be disabled
    And  "Sort desc" button in "Created at" column header should be active

  Scenario: Change list sorting to desc
    When I follow "News" menu element
    Then both sorting buttons in column header "Created at" should be active
    When I press "Sort desc" button in "Created at" column header
    Then "Sort desc" button in "Created at" column header should be disabled
    And  "Sort asc" button in "Created at" column header should be active