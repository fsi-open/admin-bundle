# How to create a simple list element in 4 steps

## 1. Create the list element class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class SubscriberElement extends ListElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Subscriber'; // Doctrine entity's class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'subscribers'; // ID is used in url generation http://domain.com/admin/list/{id}
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource(
            'doctrine',
            array('entity' => $this->getClassName()),
            'admin_subscribers' // this is the ID of the element's datasource
        );
        $datasource->setMaxResults(10);

        // To get more information about datasource you should visit https://github.com/fsi-open/datasource-bundle/blob/master/Resources/docs/basic_usage.md

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid(
            'admin_subscribers' // this is the ID of the element's datagrid
        );

        // To get more information about datagrid you should visit https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/basic_usage.md

        return $datagrid;
    }
}
```

## 2. Configure datagrid

Datagrid configuration is read from your bundle's `Resources/config/datagrid`
directory. Each element's datagrid configuration needs to be stored in a file,
whose name needs to correspond to the value passed as the datagrid's ID in the `initDataGrid`
method.

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_subscribers.yml

columns:
  email:
    type: text
    options:
      label: Email address
  active:
    type: boolean
    options:
      label: Active
```

[DataGrid column types reference](https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/columns.md)

You should also read [how to create edit and display links at list](how_to_create_edit_link_at_list.md) to better
understand what's going on under the hood.

## 3. Configure datasource

The configuration is almost identical to datagrid's. Files are read from `Resources/config/datasource`
directory and their names need to correspond to the ID passed in the `initDataSource` method.

```
# src/FSi/Bundle/DemoBundle/Resources/config/datasource/admin_subscribers.yml

fields:
  email:
    type: text
    comparison: like
  active:
    type: boolean
    comparison: eq
```

[DataSource column types reference](https://github.com/fsi-open/datasource-bundle/blob/master/Resources/docs/columns.md)

## 4. Add element to the main menu

By default elements are not visible in menu. You need to add them manually in the `admin_menu.yml` file:

```yaml
# app/config/admin_menu.yml

menu:
  - subscribers # This will use the element's ID as the label
  # This will add an element with ID "subscribers" and label "Subscribers Element" - you can also
  # use translation keys as names
  - { "id": subscriber, "name": "Subscribers Element" }

```

Remember to use the ID of element that is returned by the ``SubscriberElement::getId`` method.
You can read more about menu configuration in [Menu section](menu.md)

[Back to index](index.md)
