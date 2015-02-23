Feature: Overwriting default CRUD element templates
  In order to modify default CRUD element templates
  As a developer
  I need to install FSiAdminBundle and configure admin element

  Scenario: Services configuration
    Given the following admin elements were registered
      | Id          | Class                               |
      | custom_news | FSi\FixturesBundle\Admin\CustomNews |
    And "custom_news" element have following options defined
      | Option               | Value                                      |
      | template_crud_list   | @FSiFixtures/Admin/custom_list.html.twig   |
      | template_crud_create | @FSiFixtures/Admin/custom_edit.html.twig   |
      | template_crud_edit   | @FSiFixtures/Admin/custom_edit.html.twig   |

  Scenario: Display custom list view
    And I am on the "Custom news list" page
    Then I should see customized "list" view

  Scenario: Display custom edit view
    Given there is news with id 1 in database
    And I am on the "Custom news edit" page with id 1
    Then I should see customized "edit" view