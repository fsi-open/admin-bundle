Feature: Creating new object
  In order to allow creating new objects
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
    And following translations are available
      | Key                           | Translation   |
      | admin.news.list.title         | Title         |
      | admin.news.list.created_at    | Created at    |
      | admin.news.list.visible       | Visible       |
      | admin.news.list.creator_email | Creator email |

    Scenario: Display create form
      Given I am on the "Admin panel" page
      When I follow "News" menu element
      And I press "New element" link
      Then I should see "News Create" page header "New element"
      And I should see form with following fields
      | Field name    |
      | Title         |
      | Created at    |
      | Visible       |
      | Creator email |

    Scenario: Create new element
      Given I am on the "News Create" page
      When I fill all form field properly
      And I press form "Save" button
      Then new news should be created
      And I should be redirected to "News List" page