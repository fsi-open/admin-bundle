# Installation and Configuration

## 1. Download Admin Bundle

Add to composer.json

```
"require": {
    "fsi/admin-bundle": "1.0.*@dev"
}
```

## 2. Register bundel in application

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
    );
}
```

## 3. Set route path to AdminController

```
# app/config/routing.yml

admin:
    resource: "@FSiAdminBundle/Resources/config/routing/admin.yml"
    prefix: /admin
```

## 4. Execute commands

```
$ php app/console cache:clear
$ php app/console assets:install
```

## 5. Create admin elements

This is basic element class. In this example we create only one admin element but you can
define as many of them as you need.
Currently you can choose admin object type from:
* ``Doctrine\AbstractAdminElement``

```php
<?php
// src/FSi/DemoBundle/Admin/News.php

namespace FSi\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement;

class News extends AbstractAdminElement
{
    public function getName()
    {
        // Object name that is translated in default views.
        return 'demo.admin.element.news.name';
    }

    public function getId()
    {
        // Identifier used in urls
        return 'news';
    }

    public function getClassName()
    {
        // Entity bound to this admin object.
        return 'FSiDemoBundle:News';
    }

    protected function initDataGrid()
    {
        // This is houw you should access DataGridFactory object.
        $datagrid = $this->getDataGridFactory()->createDataGrid('news_grid');

        $datagrid->addColumn('id', 'number', array(
            'label' => 'ID'
        ));

        $datagrid->addColumn('title', 'text', array(
            'label' => 'Title',
            'editable' => true,
            'form_options' => array(
                'title' => array(
                    'attr' => array('placeholder' => 'Title...')
                )
            )
        ));

        $datagrid->addColumn('author', 'entity', array(
            'label' => 'Author',
            'value_format' => "<a href=\"mailto:%s\">%s %s</a>",
            'relation_field' => 'author',
            'field_mapping' => array('id', 'name', 'surname'),
            'editable' => true,
            'form_options' => array(
                'author' => array(
                    'class' => 'FSiDemoBundle:User'
                )
            )
        ));

        /**
         * $this->getDataGridActionColumnOptions() - this method will generate
         * options for action column. It will add edit/delete buttons in column.
         */
        $datagrid->addColumn('actions', 'action', array_merge(
            $this->getDataGridActionColumnOptions(),
            array(
                'label' => "Actions"
            )
        ));

        return $datagrid;
    }

    // This method should be used only if you need to export your data.
    // Export datagrid should be should be lighter than grid from initDataGrid()
    // You should remove "editable" option from each column and also action column should not be
    // used in it.
    protected function initExportDataGrid()
    {
        // IMPORTANT - remember to set different name for grid than is used in initDataGrid()
        $datagrid = $this->getDataGridFactory()->createDataGrid('news_export_grid');

        $datagrid->addColumn('id', 'number', array(
            'label' => 'ID'
        ));

        $datagrid->addColumn('active', 'boolean', array(
            'label' => 'Active',
            'true_value' => 'YES',
            'false_value' => 'NO',
        ));

        $datagrid->addColumn('createdAt', 'datetime', array(
            'label' => 'Created/Updated',
            'input_type' => 'datetime',
            'datetime_format' => 'Y-m-d H:i:s'
        ));

        $datagrid->addColumn('updatedAt', 'datetime', array(
            'label' => 'Created/Updated',
            'input_type' => 'datetime',
            'datetime_format' => 'Y-m-d H:i:s'
        ));

        $datagrid->addColumn('title', 'text', array(
            'label' => 'Title',
        ));

        $datagrid->addColumn('author', 'entity', array(
            'label' => 'Author',
            'value_format' => "%s %s",
            'relation_field' => 'author',
            'field_mapping' => array('name', 'surname'),
        ));

        return $datagrid;
    }

    protected function initDataSource()
    {
        $datasource = $this->getDataSourceFactory()
            ->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:News'), 'news_source');
        $datasource->setMaxResults(5);

        $datasource->addField('id', 'number', 'eq', array(
            'default_sort' => 'asc',
            'form_filter' => false
        ));

        $datasource->addField('title', 'text', 'like', array(
            'form_options' => array()
        ));

        $datasource->addField('author', 'text', 'eq', array(
            'form_type' => 'entity',
            'form_options' => array(
                'class' => 'FSiDemoBundle:User'
            )
        ));

        return $datasource;
    }

    // This method should be used only if you need to export your data.
    protected function initExportDataSource()
    {
        $datasource = $this->getDataSourceFactory()
            ->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:News'), 'news_export_source');

        return $datasource;
    }

    // This form will be used to both create and edit entity actions.
    // You can separate them using initCreateForm and initUpdateForm methods.
    protected function initForm($data = null)
    {
        if (!isset($data)) {
            $data = new \FSi\DemoBundle\Entity\News();
        }

        $builder = $this->getFormFactory()->createBuilder('form', $data);

        $builder->add('title', 'text', array(
            'label' => 'Title',
            'label_attr' => array('class' => 'control-label')
        ));

        $builder->add('author', 'entity', array(
            'class' => 'FSiDemoBundle:User',
            'empty_value' => ' -- ',
            'translation_domain' => 'FSiDemoBundle',
            'label' => 'Author',
            'label_attr' => array('class' => 'control-label')
        ));

        return $builder->getForm();
    }
}

```

As you can see admin basically needs to overwrite few methods to create fully functional admin panel for
specific types of objects.
Available methods are:

* ``protected function initDataGrid()``
* ``protected function initExportDataGrid()``
* ``protected function initDataSource()``
* ``protected function initExportDataSource()``
* ``protected function initForm($data = null)``
* ``protected function initCreateForm($data = null)``
* ``protected function initEditForm($data = null)``

### 5. Register admin elements as services

```xml
<!-- src/FSi/DemoBundle/Resources/config/services.xml -->
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- ... -->

        <!-- Define admin elements -->
        <service id="admin.element.news" class="FSi\DemoBundle\Admin\News" />

        <!-- ... -->
    </services>

</container>
```

## 6. Create groups that will aggregate admin elements.

Group is an abstract object that aggregate admin elements into logical package.
There must be at least one group registred in your application. Admin elements that are not added into any groups
are simply not accessible.

Create services.

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- ... -->

        <!-- Define groups -->
        <service id="admin.group.basic_elements" class="%admin.group.class%">
            <argument>demo.admin.group.basic_elements.name</argument>
            <argument>demo.admin.group.basic_elements.description</argument>
        </service>

        <!-- ... -->
    </services>

</container>

```

Add created groups to configuration.

```yaml
# app/config/config.yml

# FSi Admin Bundle Configuration
fsi_admin:
    groups:
        admin.group.basic_elements :
            elements:
                admin.element.news: ~
```

You can also pass some additional options to each admin element object.
Example:

```yaml
# app/config/config.yml

# FSi Admin Bundle Configuration
fsi_admin:
    groups:
        admin.group.basic_elements :
            elements:
                admin.element.news:
                    options:
                        allow_delete: false
```

### 7. Configuration Options

``\FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement``

```yaml
# app/config/config.yml

# FSi Admin Bundle Configuration
fsi_admin:
    groups:
        admin.group.basic_elements :
            elements:
                admin.element.news: #
                    options:
                        allow_delete: true
                        template_crud_list: "@FSiDemo/Crud/list.html.twig"
                        template_crud_create: "@FSiDemo/Crud/create.html.twig"
                        template_crud_edit: "@FSiDemo/Crud/edit.html.twig"
```
