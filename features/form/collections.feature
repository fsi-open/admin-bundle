Feature: Managing form collections

  Background:
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And translations are enabled in application

  @javascript
  Scenario: Adding new elements to collection
    And I am on the "Admin panel" page
    And I follow "News" menu element
    And I press "New element" link
    And I should see "News create" page header "New element"
    And I fill all form field properly
    And "Tags" collection should have 0 elements
    When I press "Add value" in collection "Tags"
    And "Tags" collection should have 1 elements
    And I fill "Name" with "Lorem" in collection "Tags" at position 1
    And I press form "Save" button
    Then new news should be created
    And I should be redirected to "News List" page
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: Add element to existing collection
    And there is 1 news in database
    And I am on the "Admin panel" page
    And I follow "News" menu element
    When I press "Edit" link in "Action" column of first element at list
    And I should see "News Edit" page header "Edit element"
    And "Tags" collection should have 1 elements
    When I press "Add value" in collection "Tags"
    And I fill "Name" with "Lorem" in collection "Tags" at position 2
    And "Tags" collection should have 2 elements
    And I press form "Save" button
    Then I should be redirected to "News List" page
    And news should have 2 elements in collection "Tags"
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: remove element from collection
    And there is 1 news in database
    And I am on the "Admin panel" page
    And I follow "News" menu element
    When I press "Edit" link in "Action" column of first element at list
    And I should see "News Edit" page header "Edit element"
    And "Tags" collection should have 1 elements
    And I remove first element in collection "Tags"
    And "Tags" collection should have 0 elements
    And I press form "Save" button
    Then I should be redirected to "News List" page
    And news should have 0 elements in collection "Tags"
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  @javascript
  Scenario: disabled buttons in collections not allowing adding or deleting items
    And there is 1 news in database
    And I am on the "Admin panel" page
    And I follow "News" menu element
    When I press "Edit" link in "Action" column of first element at list
    And I should see "News Edit" page header "Edit element"
    And non-editable collection "Non-editable tags" should have 3 elements
    Then all buttons for adding and removing items in non-editable collection "Non-editable tags" should be disabled
