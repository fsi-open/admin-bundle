Feature: Admin panel main page
  In order to generate admin panel for Symfony2 project
  As a developer
  I need to install FSiAdminBundle and configure few admin elements

  Scenario: Admin panel top bar appearance
    Given the following services were registered
      | Id                                  | Class                                | Tag           | Tag alias |
      | fixtures_bundle.admin.news          | FSi\FixturesBundle\Admin\News        | admin.element |           |
      | fixtures_bundle.admin.about_us_page | FSi\FixturesBundle\Admin\AboutUsPage | admin.element | structure |
    And translations are enabled in application
    And I am on the "Admin panel" page
    Then I should see "Admin" title at top bar
    And menu with following elements should be visible at the top of the page
      | Element name  | Element group  |
      | News          |                |
      | Subscribers   |                |
      | About us page | Site structure |
      | Home page     | Site structure |
