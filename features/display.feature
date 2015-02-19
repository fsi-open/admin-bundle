Feature: View object
  In order to view object's details
  As a developer
  I need to install FSiAdminBundle and configure display admin element

  Scenario: Accessing display object page
    Given the following admin elements were registered
      | Id           | Class                                |
      | news         | FSi\FixturesBundle\Admin\News        |
      | news_display | FSi\FixturesBundle\Admin\DisplayNews |
    And there is 1 news in database
    And translations are enabled in application
    And I am on the "News list" page
    When I press "Display" link in "Action" column of first element at list
    Then I should see "News Display" page header "Display element"
    And I should see display with following fields
      | Field name    |
      | Identity      |
      | Title         |
      | Date          |
      | Created at    |
      | Creator email |
