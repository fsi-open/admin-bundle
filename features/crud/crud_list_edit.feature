Feature: Editing existing object
  In order to allow editing existing news
  As a developer
  I need to install FSiAdminBundle and configure form in news admin element

  Scenario: Display edit form
    Given the following admin elements were registered
      | Id   | Class                         |
      | news | FSi\FixturesBundle\Admin\News |
    And there is 1 news in database
    And translations are enabled in application
    And I am on the "News list" page
    When I press "Edit" link in "Action" column of first element at list
    Then I should see "News Edit" page header "Edit element"
    And I should see form with following fields
      | Field name    |
      | Title         |
      | Date          |
      | Created at    |
      | Visible       |
      | Creator email |

  Scenario: Edit element
    Given there is news with id 1 in database
    And I am on the "News Edit" page with id 1
    When I change form "Title" field value
    And I press form "Save" button
    Then news with id 1 should have changed title
    And I should be redirected to "News List" page