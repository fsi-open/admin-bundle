Feature: Change admin panel language
  In order to change language of admin panel
  As a administrator
  I need to choose language from list in top navigation bar

  Scenario: Change panel language
    Given I am on the "Admin panel" page
    Then I should see language dropdown button in navigation bar with text "Language (English)"
    And language dropdown button should have following links
      | Link    |
      | English |
      | Polish  |
    When I click "Polish" link from language dropdown button
    Then I should see language dropdown button with text "Język (polski)"
    And language dropdown button should have following links
      | Link      |
      | angielski |
      | polski    |
