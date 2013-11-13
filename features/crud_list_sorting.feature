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
      | created_at    | sortable    | true  |
      | creator_email | sortable    | true  |

  Scenario: Display sort links in column header where column name is equal to field name and sortable field option is true
    When I follow "News" menu element
    Then I should see list with following columns
      | Column name   | Sortable |
      | Batch         | false    |
      | Title         | false    |
      | Created at    | true     |
      | Visible       | false    |
      | Creator email | true     |