Feature: Filtering elements at dependent list
  In order to allow filtering elements at dependent list
  As a developer
  I need to install FSiAdminBundle and configure parent admin element and child admin element


  Scenario: Display filters
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And translations are enabled in application
    And there is 1 "category"
    When I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should see simple text filter "Title"
    And I should see between filter "Created at" with "from" and "to" simple text fields
    And I should see simple text filter "Creator email"
    And I should see choice filter "Visible"


  Scenario: Fill filter and press the Search button
    Given there is 1 "category"
    And there is a "news" with "title" "Lorem ipsum" present in the database
    And there is a "news" with "title" "Neque porro" present in the database
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    And there are 2 elements at list
    When I fill simple text filter "Title" with value "Lorem ipsum"
    And I select "yes" in choice filter "Visible"
    And I press "Search" button
    Then I should be on the "Category news list" page
    And simple text filter "Title" should be filled with value "Lorem ipsum"
    And there should be 1 element at list
