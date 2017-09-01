# How to create a simple CRUD element in 5 steps

## 1. Create the element's class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/UserElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\Bundle\DemoBundle\Form\Type\UserType;
use FSi\Bundle\AdminBundle\Annotation as Admin;

class UserElement extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\User'; // Doctrine entity's class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'users'; // id is used in url generation http://domain.com/admin/{id}/list
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
            'admin_users' // this is the ID of the element's datasource
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
            'admin_users' // this is the ID of the element's datagrid
        );

        // To get more information about datagrid you should visit https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/basic_usage.md

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create(new UserType(), $data));

        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $form;
    }
}
```

## 2. Configure datagrid

Datagrid configuration is read from your bundle's `Resources/config/datagrid`
directory. Each element's datagrid configuration needs to be stored in a file,
whose name needs to correspond to the value passed as the datagrid's ID in the
`initDataGrid` method.

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_users.yml

columns:
  email:
    type: text
    options:
      label: Email address
  enabled:
    type: boolean
    options:
      label: Enabled
  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      admin_edit_element_id: admin_users
```

[DataGrid column types reference](https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/columns.md)

You should also read [how to create edit and display links at list](how_to_create_edit_link_at_list.md) to better
understand what's going on under the hood.

## 3. Configure datasource

The configuration is almost identical to datagrid's. Files are read from `Resources/config/datasource`
directory and their names need to correspond to the ID passed in the `initDataSource` method.

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datasource/admin_users.yml

fields:
  email:
    type: text
    comparison: like
  enabled:
    type: boolean
    comparison: eq
```

[DataSource column types reference](https://github.com/fsi-open/datasource-bundle/blob/master/Resources/docs/columns.md)

## 4. Add element to the main menu

By default elements are not visible in menu. You need to add them manually in the `admin_menu.yml` file:

```yaml
# app/config/admin_menu.yml

menu:
  - users # This will use the element's ID as the label
  # This will add an element with ID "users" and label "Users Element" - you can also
  # use translation keys as names
  - { "id": users, "name": "Users Element" }

```

Remember to use the ID of element that is returned by the ``UserElement::getId`` method.
You can read more about menu configuration in [Menu section](menu.md)

## 5. Admin element options

There are also several options that you can use to configure an element.
This can be easily done by overwriting ``configureOptions`` method in the element's class.
Following example contains all available options with default values:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/UserElement

namespace FSi\Bundle\DemoBundle\Admin;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserElement extends CRUDElement
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "allow_delete" => true,
            "allow_add" => true,
            "template_list" => "@FSiDemo/Admin/user_list.html.twig",
            "template_form" => "@FSiDemo/Admin/user_form.html.twig",
        ));
    }
}
```

[Back to index](index.md)
