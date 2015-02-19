Feature: Overwriting default list element templates
  In order to modify default list element templates
  As a developer
  I need to install FSiAdminBundle and configure newsletter subscribers admin element

  Scenario: Services configuration
    Given the following admin elements were registered
      | Id                | Class                                     |
      | custom_subscriber | FSi\FixturesBundle\Admin\CustomSubscriber |
    And "custom_subscriber" element have following options defined
      | Option        | Value                                               |
      | template_list | @FSiFixtures/Admin/subscriber_custom_list.html.twig |

  Scenario: Display custom list view
    And I am on the "Custom subscribers list" page
    Then I should see customized "subscribers list" view
