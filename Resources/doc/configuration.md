# FSiAdminBundle configuration

Below is reference for configuration of all bundle parameters, with default values:

```yaml
# config/config.yaml

fsi_admin:
    default_locale: %locale%
    locales:
        - %locale%
    menu_config_path: %kernel.root_dir%/config/admin_menu.yaml
    templates:
        base: @FSiAdmin/base.html.twig
        index_page: @FSiAdmin/Admin/index.html.twig
        list: @FSiAdmin/List/list.html.twig
        form: @FSiAdmin/Form/form.html.twig
        crud_list: @FSiAdmin/CRUD/list.html.twig
        # by default the value of `templates.form` option is copied here,
        # but you can overwrite it
        crud_form: @FSiAdmin/Form/form.html.twig
        resource: @FSiAdmin/Resource/resource.html.twig
        display: @FSiAdmin/Display/display.html.twig
        datagrid_theme: @FSiAdmin/CRUD/datagrid.html.twig
        datasource_theme: @FSiAdmin/CRUD/datasource.html.twig
        form_theme: @FSiAdmin/Form/form_div_layout.html.twig
```

There is a separate [theme](../views/CRUD/datagrid_fsi_doctrine_extensions.html.twig) for datagrids you can use if
you register the [fsi/doctrine-extensions-bundle](https://github.com/fsi-open/doctrine-extensions-bundle).

```yaml
# config/config.yaml

fsi_admin:
    templates:
        datagrid_theme: @FSiAdmin/CRUD/datagrid_fsi_doctrine_extensions.html.twig
```
