Feature: Change admin panel language
  In order to change language of admin panel
  As a administrator
  I need to choose language from list in top navigation bar

  Scenario: Change panel language
    Given I am on the "Admin panel" page
    Then I should see language dropdown button in navigation bar with text "Language (en)"
    And language dropdown button should have following links
      | Link         |
      | English (en) |
      | Polish (pl)  |
    When I click "Polish (pl)" link from language dropdown button
    Then I should see language dropdown button with text "Polski (pl)"
    And language dropdown button should have following links
      | Link         |
      | Angielski (en) |
      | Polski (pl)  |