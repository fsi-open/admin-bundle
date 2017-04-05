Feature: Editing news title directly from list of dependent elements
  In order to allow editing values in cells directly at dependent list
  As a developer
  I need to configure news title as a editable in dependent datagrid

  Background:
    Given there is a "category" with "title" "events" present in the database
    Given the following news exist in database
      | Title  | Date       | Category |
      | News 1 |            | events   |
      | News 2 | 2013-12-31 | events   |
    When I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
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
    When I fill "Subtitle" field at popover with "News 1 Test Subtitle" value
    And I press "Save" at popover
    Then I should be on the "Category news list" page
    And there should be a "news" with "title" "News 1 Test" present in the database
