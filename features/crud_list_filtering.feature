Feature: Filtering elements at list
  In order to allow filtering elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for news admin element

  Scenario: Services configuration
    Given the following services were registered
      | Id                         | Class                         | Tag           | Tag alias |
      | fixtures_bundle.admin.news | FSi\FixturesBundle\Admin\News | admin.element |           |
    And following fields should be added to "news" element datasource
      | Field name    | Field type | Field comparison |
      | title         | text       | like             |
      | created_at    | date       | between          |
      | visible       | boolean    | eq               |
      | creator_email | text       | like             |
    And following values for "form_options" option should be defined in "news" element datasource fields
      | Field name    | Option | Value                         |
      | title         | label  | admin.news.list.title         |
      | creator_email | label  | admin.news.list.creator_email |
      | visible       | label  | admin.news.list.visible       |
    And following values for "form_from_options" option should be defined in "news" element datasource fields
      | Field name    | Option | Value                           |
      | created_at    | label  | admin.news.list.created_at_from |
    And following values for "form_to_options" option should be defined in "news" element datasource fields
      | Field name    | Option | Value                         |
      | created_at    | label  | admin.news.list.created_at_to |
    And following translations are available
      | Key                             | Translation     |
      | admin.news.list.title           | Title           |
      | admin.news.list.created_at_from | Created at from |
      | admin.news.list.created_at_to   | Created at to   |
      | admin.news.list.visible         | Visible         |
      | admin.news.list.creator_email   | Creator email   |

  Scenario: Display filters
    Given I am on the "News list" page
    Then I should see simple text filter "Title"
    And I should see between filter "Created at" with "from" and "to" simple text fields
    And I should see simple text filter "Creator email"
    And I should see choice filter "Visible"

  Scenario: Fill filter and press the Search button
    Given I am on the "News list" page
    When I fill simple text filter "Title" with value "Lorem ipsum"
    And I press "Search" button
    Then I should see filtered list
    And simple text filter "Title" should be filled with value "Lorem ipsum"