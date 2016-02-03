Feature: Creating new object
  In order to allow creating new objects
  As a developer
  I need to install FSiAdminBundle and configure form in news admin element

  Scenario: Display create form
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
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
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """
