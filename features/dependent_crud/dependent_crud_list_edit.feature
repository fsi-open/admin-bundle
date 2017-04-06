Feature: Editing existing dependent object
  In order to allow editing existing news from dependent list
  As a developer
  I need to install FSiAdminBundle and configure form in dependent news admin element


  Scenario: Display edit form
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And there is 1 "category"
    And there is 1 "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    When I press "Edit" link in actions column of first element at list
    Then I should see "Category news edit" page header "Edit element"
    And I should see form with following fields
      | Field name    |
      | Title         |
      | Date          |
      | Created at    |
      | Visible       |
      | Creator email |


  Scenario: Edit element
    Given there is 1 "category"
    And there is a "news" with "id" 1 present in the database
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    And I clicked "Edit" in "Actions" column in first row
    When I change form field "Title" to value "New title"
    And I press form "Save" button
    Then I should be redirected to "Category news list" page
    And "news" with id "1" should have changed "Title" to "New title"
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """
