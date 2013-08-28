# AdminBundle configuration

Admin panel is build from admin elements. Admin element is nothing more that element in menu. So next thing you
should do after installation is creating few admin elements.

At the moment there is only one element type **Doctrine CRUD** (Create Read Update Delete) that you can use.
Lets assume that we need to create news management section in our admin panel, this is simple example how this can
be done with Doctrine CRUD element.

## Admin object class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/News

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;

class News extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSiDemoBundle:News'; // Doctrine class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'news'; // id is used in url generation http://domain.com/admin/{id}/list
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin.apartment.name'; // names are translated in twig so you can use translation key as name
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), 'datasource');

        // Here you can add some fields into datasource
        // To get more information about datasource you should visit https://github.com/fsi-open/datasource

        return $datasource;
    }

    protected function initDataGrid(DataGridFactoryInterface $datagrid)
    {
        $datagrid = $datagrid->createDataGrid('datagrid');

        // Here you should add some columns into datagrid
        // To get more information about datasource you should visit https://github.com/fsi-open/datagrid

        return $datagrid;
    }

    protected function initCreateForm(FormFactoryInterface $factory)
    {
        $data = new \FSi\Bundle\DemoBundle\Entity\News();
        $builder = $factory->createNamedBuilder('news', 'form', $data);

        // Here you should add some fields into form
        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $builder->getForm();
    }

    protected function initEditForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder('news', 'form', $data);

        // Here you should add some fields into form
        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $builder->getForm();
    }
}
```

## News CRUD service

Every single admin element must be registered as a service with ``admin.element`` tag.
Optionally you can also use tag ``alias`` attribute to assign element into group.
Group name as element name is translated so you can use translation key as a group name (alias)

```xml

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.news" class="FSi\Bundle\DemoBundle\Admin\News">
        <tag name="admin.element"/>
    </service>

</services>
</container>

```

This should be enough to create simple admin element and display it in menu.
However sometimes you need you customize admin object. This can be done with options that you can pass as a service
collection argument.

### Doctrine CRUD Element options

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.news" class="FSi\Bundle\DemoBundle\Admin\News">
        <argument type="collection">
            <argument key="allow_delete">1</argument> <!-- 1 = true | 0 = false -->
            <argument key="template_crud_list">@FSiDemo/Admin/news_edit.html.twig</argument>
            <argument key="template_crud_create">@FSiDemo/Admin/news_create.html.twig</argument>
            <argument key="template_crud_edit">@FSiDemo/Admin/news_edit.html.twig</argument>
            <argument key="template_crud_delete">@FSiDemo/Admin/news_create.html.twig</argument>
        </argument>
        <tag name="admin.element"/>
    </service>

</services>
```

If this is not enough you can use [event system](Resources/doc/events.md) to modify admin elements before triggering actions in controllers.