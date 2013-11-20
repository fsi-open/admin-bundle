Feature: Editing existing object
  In order to allow editing existing news
  As a developer
  I need to install FSiAdminBundle and configure form in news admin element

  Scenario: Services configuration
    Given the following services were registered
      | Id                     | Class                                    | Tag           | Tag alias |
      | demo_bundle.admin.news | FSi\Behat\Fixtures\DemoBundle\Admin\News | admin.element |           |
    And following fields should be added to "news" element form
      | Field name    | Field type |
      | title         | text       |
      | created_at    | date       |
      | visible       | checkbox   |
      | creator_email | email      |
    And following options should be defined in "news" element form fields
      | Field name    | Option | Value                         |
      | title         | label  | admin.news.list.title         |
      | created_at    | label  | admin.news.list.created_at    |
      | created_at    | widget | single_text                   |
      | visible       | label  | admin.news.list.visible       |
      | creator_email | label  | admin.news.list.creator_email |
    And following columns should be added to "news" element datagrid
      | Column name   | Column type |
      | actions       | action        |
    And following translations are available
      | Key                           | Translation   |
      | admin.news.list.title         | Title         |
      | admin.news.list.created_at    | Created at    |
      | admin.news.list.visible       | Visible       |
      | admin.news.list.creator_email | Creator email |
      | admin.news.list.actions       | Actions       |

    Scenario: Display edit form
      Given there is 1 news in database
      And I am on the "News list" page
      When I press "Edit" link in "Action" column of first element at list
      Then I should see "News Edit" page header "Edit element"
      And I should see form with following fields
      | Field name    |
      | Title         |
      | Created at    |
      | Visible       |
      | Creator email |

    Scenario: Edit element
      Given there is news with id 1 in database
      And I am on the "News Edit" page with id 1
      When I change form "Title" field value
      And I press form "Save" button
      Then news with id 1 should have changed title
      And I should be redirected to "News List" page