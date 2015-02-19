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
    Given I am on the "News list" page
    When I fill simple text filter "Title" with value "Lorem ipsum"
    And I press "Search" button
    Then I should see filtered list
    And simple text filter "Title" should be filled with value "Lorem ipsum"