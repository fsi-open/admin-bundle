## Admin object class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class User extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSiDemoBundle:User'; // Doctrine class name
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
    public function getName()
    {
        return 'Users'; // names are translated in twig so you can use translation key as name
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), 'datasource');

        $datasource->addField('email', 'text', 'like');
        $datasource->addField('username', 'text', 'like');

        // Here you can add some fields or filters into datasource
        // To get more information about datasource you should visit https://github.com/fsi-open/datasource

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('datagrid');

        $datagrid->addColumn('email', 'text', array(
            'label' => 'Eamil'
        ));
        $datagrid->addColumn('username', 'text', array(
            'label' => 'Username',
            'editable' => true,
        ));
        $datagrid->addColumn('enabled', 'boolean', array(
            'label' => 'Enabled'
        ));
        $datagrid->addColumn('locked', 'boolean', array(
            'label' => 'Locked'
        ));
        $datagrid->addColumn('roles', 'text', array(
            'label' => 'Roles',
            'value_format' => function($data) {
                return implode(', ', $data['roles']);
            }
        ));
        $datagrid->addColumn('action', 'action', array(
            'label' => 'Action',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'url_attr' => array(
                        'class' => 'btn btn-warning btn-small-horizontal',
                        'title' => 'edit user'
                    ),
                    'content' => '<span class="icon-eject icon-white"></span>',
                    'route_name' => 'fsi_admin_crud_edit',
                    'parameters_field_mapping' => array(
                        'id' => 'id'
                    ),
                    'additional_parameters' => array(
                        'element' => $this->getId()
                    )
                )
            )
        ));

        // Here you can add some columns into datagrid
        // To get more information about datagrid you should visit https://github.com/fsi-open/datagrid

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form', $data, array(
            'data_class' => 'FSi\Bundle\DemoBundle\Entity\User' // this option is important for create form 
        ));

        $form->add('email', 'email');
        $form->add('username', 'text');
        $form->add('enabled', 'choice', array(
            'choices' => array(
                0 => 'No',
                1 => 'Yes',
            )
        ));
        $form->add('locked', 'choice', array(
            'choices' => array(
                0 => 'No',
                1 => 'Yes',
            )
        ));

        // Here you should add some fields into form
        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $form;
    }
}
```

## User CRUD service

Every single admin element must be registered as a service with ``admin.element`` tag.
Optionally you can also use tag ``alias`` attribute to assign element into group.
Group name same as element name is translated so you can use translation key as a group name (alias)

```xml

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.user" class="FSi\Bundle\DemoBundle\Admin\User">
        <tag name="admin.element"/>
    </service>

</services>
</container>

```

This should be enough to create simple admin element and display it in menu.
However sometimes you need you customize admin object. This can be done with options that you can pass as a service
collection argument.

## Doctrine CRUD Element options

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.user" class="FSi\Bundle\DemoBundle\Admin\User">
        <argument type="collection">
            <argument key="allow_delete">true</argument>
            <argument key="allow_add">true</argument>
            <argument key="allow_edit">true</argument>
            <argument key="crud_list_title">crud.list.title</argument>
            <argument key="crud_create_title">crud.create.title</argument>
            <argument key="crud_edit_title">crud.edit.title</argument>
            <argument key="template_crud_list">@FSiDemo/Admin/user_edit.html.twig</argument>
            <argument key="template_crud_create">@FSiDemo/Admin/user_create.html.twig</argument>
            <argument key="template_crud_edit">@FSiDemo/Admin/user_edit.html.twig</argument>
            <argument key="template_crud_delete">@FSiDemo/Admin/user_create.html.twig</argument>
        </argument>
        <tag name="admin.element"/>
    </service>

</services>
```

## Demo

![Preview of list](../preview/crud_list.png)

![Preview of create form](../preview/crud_create.png)

![Preview of edit form](../preview/crud_edit.png)

![Preview of delete](../preview/crud_delete.png)
