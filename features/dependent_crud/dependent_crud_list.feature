Feature: List of dependent elements
  In order to generate list of dependent elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure parent admin element and child admin element


  Scenario: Accessing news list from admin panel main page
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And there are 2 "categories"
    And I am on the "Admin panel" page
    When I follow "Categories" url from top bar
    And I clicked "news" in "Actions" column in first row
    Then I should be on the "Category news list" page
    And "Categories" link in the top bar should be highlighted
    And I should see list with following columns
      | Column name   |
      | Batch         |
      | Title         |
    And I should see "Category news list" page header "List of elements"
