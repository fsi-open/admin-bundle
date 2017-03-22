Feature: Filtering elements at list
  In order to allow filtering elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for news admin element


  Scenario: Display filters
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And I am on the "News list" page
    And translations are enabled in application
    Then I should see simple text filter "Title"
    And I should see between filter "Created at" with "from" and "to" simple text fields
    And I should see simple text filter "Creator email"
    And I should see choice filter "Visible"


  Scenario: Fill filter and press the Search button
    Given there is a "news" with "title" "Lorem ipsum" present in the database
    And there is a "news" with "title" "Neque porro" present in the database
    And I am on the "News list" page
    And there are 2 elements at list
    When I fill simple text filter "Title" with value "Lorem ipsum"
    And I press "Search" button
    Then simple text filter "Title" should be filled with value "Lorem ipsum"
    And there should be 1 element at list
