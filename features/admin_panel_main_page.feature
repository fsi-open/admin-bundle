Feature: Admin panel main page
  In order to generate admin panel for Symfony2 project
  As a developer
  I need to install FSiAdminBundle and configure few admin elements

  Background:
    Given the following services were registered
      | Id                              | Class                                           | Tag           | Tag alias |
      | demo_bundle.admin.news          | FSi\Behat\Fixtures\DemoBundle\Admin\News        | admin.element |           |
      | demo_bundle.admin.home_page     | FSi\Behat\Fixtures\DemoBundle\Admin\HomePage    | admin.element | structure |
      | demo_bundle.admin.about_us_page | FSi\Behat\Fixtures\DemoBundle\Admin\AboutUsPage | admin.element | structure |
    And there are following admin elements available
      | Id            | Name                     |
      | news          | admin.news.name          |
      | home_page     | admin.home_page.name     |
      | about_us_page | admin.about_us_page.name |
    And following translations are available
      | Key                      | Translation    |
      | structure                | Site structure |
      | admin.news.name          | News           |
      | admin.home_page.name     | Home page      |
      | admin.about_us_page.name | About us page  |

  Scenario: Admin panel top bar appearance
    When I open "Admin Panel" page
    Then I should see "Admin" title at top bar
    And menu with following elements should be visible at the top of the page
      | Element name  | Element group  |
      | News          |                |
      | Home page     | Site structure |
      | About us page | Site structure |