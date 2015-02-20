Feature: Managing form nested collections

  Background:
    Given the following services were registered
      | Id                              | Class                             | Tag           |
      | fixtures_bundle.admin.news      | FSi\FixturesBundle\Admin\News     | admin.element |
    And translations are enabled in application
    And there is 1 news in database
    And I am on the "Admin panel" page
    And I follow "News" menu element
    And I press "Edit" link in "Action" column of first element at list
    And I should see "News Edit" page header "Edit element"
    And "Tags" collection should have 1 elements
    And "Elements" collection should have 0 elements

  @javascript
  Scenario: Adding new elements to nested collection
    When I press "Add value" in collection "Elements"
    And I fill "Name" with "Lorem" in collection "Elements" at position 1
    And I press "Add value" in collection "Elements"
    And I fill "Name" with "Ipsum" in collection "Elements" at position 2
    Then "Elements" collection should have 2 elements
    And "Tags" collection should have 1 elements

  @javascript
  Scenario: removing elements to nested collection
    When I press "Add value" in collection "Elements"
    And I press "Add value" in collection "Elements"
    And I press "Add value" in collection "Elements"
    And I press remove 1 element in collection "Elements"
    Then "Elements" collection should have 2 elements
    And "Tags" collection should have 1 elements
