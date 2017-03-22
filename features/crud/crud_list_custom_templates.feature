Feature: Overwriting default CRUD element templates
  In order to modify default CRUD element templates
  As a developer
  I need to install FSiAdminBundle and configure admin element

  Scenario: Services configuration
    Given the following admin elements were registered
      | Id          | Class                               |
      | custom_news | FSi\FixturesBundle\Admin\CustomNews |
    And "custom_news" element have following options defined
      | Option        | Value                                      |
      | template_list | @FSiFixtures/Admin/custom_list.html.twig   |
      | template_form | @FSiFixtures/Admin/custom_form.html.twig   |



  Scenario: Display custom list view
    And I am on the "Custom news list" page
    Then page "Custom news list" should display OK status


  Scenario: Display custom edit view
    Given there is a "news" with "id" 1 present in the database
    And I am on the "Custom news edit" page with id 1
    Then page "Custom news edit" should display OK status
