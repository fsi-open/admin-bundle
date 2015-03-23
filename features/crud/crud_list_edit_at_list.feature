Feature: Editing news title directly from list of elements
  In order to allow editing values in cells directly at list
  As a developer
  I need to configure news title as a editable in datagrid

  Background:
    Given the following news exist in database
      | Title  | Date       |
      | News 1 |            |
      | News 2 | 2013-12-31 |
    And I am on the "News list" page
    And "Title" column is editable

  @javascript
  Scenario: Display popover with news title edit form
    When I click edit in "Title" column in first row
    Then popover with "News 1" field in form should appear

  @javascript
  Scenario: Display popover with news date when date is not set
    When I click edit in "Date" column in first row
    Then popover with empty date field in form should appear

  @javascript
  Scenario: Hide popover with news title edit form
    Given I clicked edit in "Title" column in first row
    When I click X at popover
    Then popover should not be visible anymore

  @javascript
  Scenario: Edit news title via popover form
    Given I clicked edit in "Title" column in first row
    When I fill "Title" field at popover with "News 1 Test" value
    And I press "Save" at popover
    Then there should be news with "News 1 Test" title in database