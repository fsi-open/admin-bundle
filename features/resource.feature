Feature: Editing page resources
  In order to allow editing application resources
  As a developer
  I need to install FSiAdminBundle and configure resource admin element

  Scenario: Accessing resource edit page
    Given the following services were registered
      | Id                              | Class                             | Tag           |
      | fixtures_bundle.admin.home_page | FSi\FixturesBundle\Admin\HomePage | admin.element |
    And I am on the "Admin panel" page
    And translations are enabled in application
    When I follow "Home page" menu element
    Then I should see "Home page edit" page header "Edit resources"

  Scenario: Display form build from resource map
    Given there are following resources added to resource map
      | Key                        | Type |
      | resources.homepage.content | text |
      | resources.homepage.header | text |
    And I am on the "Home page edit" page
    Then I should see form with following fields
      | Field name    |
      | Content       |
      | Header        |
