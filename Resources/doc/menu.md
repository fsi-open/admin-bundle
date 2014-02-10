# Admin panel menu

Menu is displayed in the upper part of admin panel, on the black navigation bar.
By default every single service that implements ``ElementInterface`` and is created
with tag ``admin.element`` is added to admin panel menu.

## Remove element from menu

To remove element from menu you should register it with option ``menu => false``

Example:

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="fixtures_bundle.admin.news" class="FSi\FixturesBundle\Admin\News">
            <argument type="collection">
                <argument key="menu">false</argument>
            </argument>
            <tag name="admin.element" />
        </service>
        <service id="fixtures_bundle.admin.home_page" class="FSi\FixturesBundle\Admin\HomePage">
            <tag name="admin.element" alias="structure" />
        </service>
    </services>
</container>
```

In above example admin panel menu will contain only ``HomePage`` element. ``News`` element will
not be included in menu thanks to ``<argument key="menu">false</argument>`` option.
