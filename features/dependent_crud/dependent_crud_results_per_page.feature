Feature: Changing results per page at dependent list page
  In order to generate list of dependent elements in admin panel
  As a developer
  I need to install FSiAdminBundle and configure parent admin element and child admin element

  Background:
    Given "category_news" element datasource max results is set 10


  Scenario: Display list with dependent elements count equal to datasource max results
    Given the following admin elements were registered
      | Id            | Class                                 | Parent   |
      | category_news | FSi\FixturesBundle\Admin\CategoryNews | category |
      | category      | FSi\FixturesBundle\Admin\Category     |          |
    And there is 1 "category"
    And there are 20 "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then there should be 10 elements at list


  Scenario: Change elements per page
    Given there is 1 "category"
    And there are 20 "news"
    And I am on the "Category list" page
    And I clicked "news" in "Actions" column in first row
    Then I should be on the "Category news list" page
    And there should be 10 elements at list
    When I change elements per page to 5
    Then I should be on the "Category news list" page
    And there should be 5 elements at list
    When I change elements per page to 50
    Then I should be on the "Category news list" page
    And there should be 20 elements at list
