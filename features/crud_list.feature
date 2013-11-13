Feature: List of elements
  In order to generate list of elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure datagrid for news admin element

  Background:
    Given I am on the "Admin panel" page
    And the following services were registered
      | Id                     | Class                                    | Tag           | Tag alias |
      | demo_bundle.admin.news | FSi\Behat\Fixtures\DemoBundle\Admin\News | admin.element |           |
    And following columns should be added to "news" element datagrid
      | Column name   | Column type |
      | title         | text        |
      | created_at    | datetime    |
      | visible       | boolean     |
      | creator_email | text        |
    And following options should be defined in "news" element datagrid columns
      | Column name   | Option name | Option value                  |
      | title         | label       | admin.news.list.title         |
      | title         | editable    | true                          |
      | created_at    | label       | admin.news.list.created_at    |
      | visible       | label       | admin.news.list.visible       |
      | creator_email | label       | admin.news.list.creator_email |
    And following translations are available
      | Key                           | Translation   |
      | admin.news.list.title         | Title         |
      | admin.news.list.created_at    | Created at    |
      | admin.news.list.visible       | Visible       |
      | admin.news.list.creator_email | Creator email |

  Scenario: Accessing news list from admin panel main page
    When I follow "News" url from top bar
    Then I should see list with following columns
      | Column name   |
      | Batch         |
      | Title         |
      | Created at    |
      | Visible       |
      | Creator email |