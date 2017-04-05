Feature: Creating new object from dependent list
  In order to allow creating new objects from dependent
  As a developer
  I need to install FSiAdminBundle and configure form in dependent news admin element


  Scenario: Display create form
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And there is "1" "category"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    And I press "New element" link
    Then I should see "Category news create" page header "New element"
    And I should see form with following fields
      | Field name    |
      | Title         |
      | Date          |
      | Created at    |
      | Visible       |
      | Creator email |


  Scenario: Create new element
    Given there is "1" "category"
    And there is "0" "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    And I press "New element" link
    When I fill the form with values:
      | Field name    | Field value       |
      | Title         | A new news        |
      | Created at    | 2017-03-01        |
      | Creator email | email@example.com |
      | Visible       | Yes               |
    And I press form "Save" button
    Then new "news" should be created
    And I should be redirected to "Category news list" page
    And there should be 1 element at list
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """
