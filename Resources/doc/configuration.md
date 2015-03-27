# FSiAdminBundle configuration

There are some things that can be configured globally for all admin elements.
This is reference for admin bundle configuration with all default options.

```yml
# app/config/config.yml

fsi_admin:
    display_language_switch: false
    templates:
        base: @FSiAdmin/base.html.twig
        index_page: @FSiAdmin/Admin/index.html.twig
        crud_list: @FSiAdmin/CRUD/list.html.twig
        crud_create: @FSiAdmin/CRUD/create.html.twig
        crud_edit: @FSiAdmin/CRUD/edit.html.twig
        crud_delete: @FSiAdmin/CRUD/delete.html.twig
        resource: @FSiAdmin/Resource/resource.html.twig
        datagrid_theme: @FSiAdmin/CRUD/datagrid.html.twig
        datasource_theme: @FSiAdmin/CRUD/datasource.html.twig
        edit_form_theme: @FSiAdmin/Form/form_div_layout.html.twig
        create_form_theme: @FSiAdmin/Form/form_div_layout.html.twig
        delete_form_theme: @FSiAdmin/Form/form_div_layout.html.twig
        resource_form_theme: @FSiAdmin/Form/form_div_layout.html.twig
```

There is a separate [theme](../view/CRUD/datagrid_fsi_doctrine_extensions.html.twig) for datagrids you can use if
you register [fsi/doctrine-extensions-bundle](https://github.com/fsi-open/doctrine-extensions-bundle). 

```yml
# app/config/config.yml

fsi_admin:
    templates:
        datagrid_theme: @FSiAdmin/CRUD/datagrid_fsi_doctrine_extensions.html.twig
```
