Feature: Creating new object
  In order to allow creating new objects
  As a developer
  I need to install FSiAdminBundle and configure form in news admin element

  Scenario: Display form for new element
    Given the following admin elements were registered
      | Id              | Class                                   |
      | subscriber      | FSi\FixturesBundle\Admin\Subscriber     |
      | subscriber_form | FSi\FixturesBundle\Admin\SubscriberForm |
    And translations are enabled in application
    And I am on the "Admin panel" page
    When I follow "Subscribers" menu element
    And I press "New element" link
    Then I should see "Subscriber Form" page header "New element"
    And I should see form with following fields
      | Field name    |
      | Email         |
      | Created at    |
      | Active        |

  Scenario: Create new element
    Given I am on the "Subscriber Form" page
    When I fill all form field properly
    And I press form "Save" button
    Then new subscriber should be created
    And I should be redirected to "Subscribers List" page
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  Scenario: Display form for existing element
    Given the following admin elements were registered
      | Id              | Class                                   |
      | subscriber      | FSi\FixturesBundle\Admin\Subscriber     |
      | subscriber_form | FSi\FixturesBundle\Admin\SubscriberForm |
    And there is 1 subscriber in database
    And translations are enabled in application
    And I am on the "Subscribers list" page
    When I press "Edit" link in "Action" column of first element at list
    Then I should see "Subscriber Form" page header "Edit element"
    And I should see form with following fields
      | Field name    |
      | Email         |
      | Created at    |
      | Active        |

  Scenario: Edit element
    Given there is subscriber with id 1 in database
    And I am on the "Subscriber Edit" page with id 1
    When I change form "Email" field value
    And I press form "Save" button
    Then subscriber with id 1 should have changed email
    And I should be redirected to "Subscribers List" page
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """

  Scenario: Editing an element with invalid data
    Given there is subscriber with id 1 in database
    And I am on the "Subscriber Edit" page with id 1
    When I fill the "Email" field with invalid data
    And I press form "Save" button
    Then subscriber with id 1 should not have his email changed to invalid one
    And I should see an error message saying:
    """
    Form is invalid.
    """

  Scenario: Opening new object form page for edit only element
    Given there is a person with id 1 in database
    And I am on the "Person edit form" page with id 1
    Then I should see form with following fields
      | Field name    |
      | Email         |
    Given I try to open the "Person add form" page
    Then page "Person add form" should display not found exception

  Scenario: Submitting a DTO form element
    Given I am on the "DTO Form" page
    When I change form "Email" field value
    And I press form "Save" button
    Then I should be redirected to "DTO Form" page
    And I should see a success message saying:
    """
    Data has been successfully saved.
    """
