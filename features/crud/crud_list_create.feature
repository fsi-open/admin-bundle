Feature: Creating new object
  In order to allow creating new objects
  As a developer
  I need to install FSiAdminBundle and configure form in news admin element

  Scenario: Display create form
    Given the following services were registered
      | Id                         | Class                         | Tag           |
      | fixtures_bundle.admin.news | FSi\FixturesBundle\Admin\News | admin.element |
    And translations are enabled in application
    And I am on the "Admin panel" page
    When I follow "News" menu element
    And I press "New element" link
    Then I should see "News Create" page header "New element"
    And I should see form with following fields
      | Field name    |
      | Title         |
      | Date          |
      | Created at    |
      | Visible       |
      | Creator email |

  Scenario: Create new element
    Given I am on the "News Create" page
    When I fill all form field properly
    And I press form "Save" button
    Then new news should be created
    And I should be redirected to "News List" page
