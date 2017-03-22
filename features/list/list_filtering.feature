Feature: Filtering elements at list
  In order to allow filtering elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for newsletter subscribers admin element


  Scenario: Display filters
    Given the following admin elements were registered
      | Id         | Class                               |
      | subscriber | FSi\FixturesBundle\Admin\Subscriber |
    And I am on the "Subscribers list" page
    And translations are enabled in application
    Then I should see simple text filter "Email"
    And I should see between filter "Created at" with "from" and "to" simple text fields
    And I should see choice filter "Active"


  Scenario: Do not display filters if not necessary
    Given the following admin elements were registered
      | Id          | Class                               |
      | custom_news | FSi\FixturesBundle\Admin\CustomNews |
    And "custom_news" element has datasource with fields
    But "custom_news" element has datasource without filters
    And I am on the "Custom news list" page
    Then I should not see any filters


  Scenario: Fill text filter and press the Search button
    Given there is a "subscriber" with "email" "subscriber@example.com" present in the database
    And there is a "subscriber" with "email" "subscriber@domain.com" present in the database
    And I am on the "Subscribers list" page
    And there are 2 elements at list
    When I fill simple text filter "Email" with value "@domain.com"
    And I select "yes" in choice filter "Active"
    And I press "Search" button
    Then I should be on the "Subscribers list" page
    And simple text filter "Email" should be filled with value "@domain.com"
    And there should be 1 element at list


  Scenario: Fill boolean filter and press the Search button
    Given there is a "subscriber" with "active" "false" present in the database
    And there is a "subscriber" with "active" "true" present in the database
    And I am on the "Subscribers list" page
    And there are 2 elements at list
    When I select "yes" in choice filter "Active"
    And I press "Search" button
    Then choice filter "Active" should have value "yes" selected
    And there should be 1 element at list
