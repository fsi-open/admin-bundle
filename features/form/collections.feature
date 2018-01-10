Feature: Managing form collections

  Background:
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And translations are enabled in application

  @javascript
  Scenario: Adding new elements to a collection
    Given I am on the "News create" page
    When I fill the form with values:
      | Field name    | Field value       |
      | Title         | A new news        |
      | Created at    | 2017-03-01        |
      | Creator email | email@example.com |
      | Visible       | Yes               |
    And "Tags" collection has 0 elements
    When I press "Add value" in collection "Tags"
    Then "Tags" collection should have 1 element
    When I fill "Name" with "Lorem" in collection "Tags" at position 1
    And I press form "Save" button
    Then new "news" should be created
    And I should be redirected to "News list" page
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: Add an element to an existing collection
    Given there is a "news" with "title" "News 1" present in the database
    And I am on the "News edit" page with id "1"
    And "Tags" collection has 1 element
    When I press "Add value" in collection "Tags"
    Then "Tags" collection should have 2 elements
    When I fill "Name" with "Lorem" in collection "Tags" at position 2
    And I press form "Save" button
    Then I should be redirected to "News list" page
    And "news" with "title" "News 1" should have 2 elements in collection "Tags"
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: Remove an element from collection
    Given there is a "news" with "title" "News 1" present in the database
    And I am on the "News edit" page with id "1"
    And "Tags" collection has 1 element
    When I remove first element in collection "Tags"
    Then "Tags" collection should have 0 elements
    When I press form "Save" button
    Then I should be redirected to "News list" page
    And "news" with "title" "News 1" should have 0 elements in collection "Tags"
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: Disabled buttons in collections should not allow adding or deleting items
    Given there is a "news" with "title" "News 1" present in the database
    And I am on the "News edit" page with id "1"
    Then non-editable collection "Non-editable tags" should have 3 elements
    And removable-only collection "Removable-only comments" should have 3 elements
    And all buttons for adding and removing items in non-editable collection "Non-editable tags" should be disabled
    And button for adding item in removable-only collection "Removable-only comments" should be disabled
    And buttons for removing items in removable-only collection "Removable-only comments" should be enabled
    When I remove first element in removable-only collection "Removable-only comments"
    Then removable-only collection "Removable-only comments" should have 2 elements
