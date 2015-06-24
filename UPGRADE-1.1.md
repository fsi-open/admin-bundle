# UPGRADE from 1.0.* to ~1.1

This document describes steps needed to upgrade admin-bundle from 1.0.* version to ~1.1. Some of them always
require attention due to some BC breaks in 1.1 version, some of them often need some change and some are mentioned but
almost never should have been used in real use cases. For full list of changes go to
[CHANGELOG-1.1.md](CHANGELOG-1.1.md).

## Change your composer.json (always)

```yaml
# ...
    'fsi/admin-bundle': '~1.1'
# ...
```

**Version 1.1 drops support for symfony lower than 2.6 so if you are still using symfony < 2.6 you will have
to upgrade it too.**

## Build admin menu (always)

Method ``ElementInterface::getName()`` has been removed, so you must create ``app/config/admin_menu.yml``
to organize your administration menu. This new feature is described [here](Resources/doc/menu.md).

## Remove non existing admin elements' options (often)

Admin element option ``menu`` has been removed because of completely new menu building system based on
``app/config/admin_menu.yml`` config file.

Admin element options ``crud_list_title``, ``crud_create_title``, ``crud_edit_title`` and ``title`` have been
removed, so if you are using one of these options you will get an exception. To preserve old titles you must set
corresponding templates and overwrite block ``header`` in them.

CRUD element options ``template_crud_create`` and ``template_crud_edit`` are not allowed to have different values
now. You can differentiate template for item creation and edition it is possible using conditions in the common form
template.

## Reorganize admin elements' templates (average often)

### CRUD element list template

It no longer has blocks named ``batch_action`` and ``batch_form``, because batch actions are now configured through
batch column options in datagrid.

All partials ``Resources/views/CRUD/List/*.html.twig`` have been removed so overwriting them in ``app/Resources`` has no
longer any effect. Alternatively you can set another template for in application config and overwrite some of its
blocks.

### Custom form themes

Template ``Resources/views/Form/form_div_layout.html.twig`` was renamed to ``Resources/views/Form/form_theme.html.twig``
so if you have any custom form theme which extends the old one, it must be adjusted.

### CRUD delete template

This template has been removed so overwriting it has no longer any effect.

## Remove non existing bundle configuration options (average often)

Please refer [here](CHANGELOG-1.1.md#removed-configuration-options) for full list of removed configuration options.

## Change admin events' names (average often)

Please refer [here](CHANGELOG-1.1.md#event-names-changes) for full map of changed event names between version 1.0 and 
version 1.1
