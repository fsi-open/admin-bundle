Feature: Filtering elements at list
  In order to allow filtering elements at list
  As a developer
  I need to install FSiAdminBundle and configure datasource for newsletter subscribers admin element

  Scenario: Display filters
    Given the following services were registered
      | Id                               | Class                               | Tag           |
      | fixtures_bundle.admin.subscriber | FSi\FixturesBundle\Admin\Subscriber | admin.element |
    And I am on the "Subscribers list" page
    And translations are enabled in application
    Then I should see simple text filter "Email"
    And I should see between filter "Created at" with "from" and "to" simple text fields
    And I should see choice filter "Active"

  Scenario: Do not display filters if not necessary
    Given the following services were registered
      | fixtures_bundle.admin.custom_news | FSi\FixturesBundle\Admin\CustomNews | admin.element |
    And "custom_news" element has datasource with fields
    But "custom_news" element has datasource without filters
    And I am on the "Custom News list" page
    Then I should not see any filters

  Scenario: Fill text filter and press the Search button
    Given I am on the "Subscribers list" page
    When I fill simple text filter "Email" with value "@domain.com"
    And I press "Search" button
    Then I should see filtered list
    And simple text filter "Email" should be filled with value "@domain.com"

  Scenario: Fill boolean filter and press the Search button
    Given I am on the "Subscribers list" page
    When I select "yes" in choice filter "Active"
    And I press "Search" button
    Then I should see filtered list
    And choice filter "Active" should have value "yes" selected
