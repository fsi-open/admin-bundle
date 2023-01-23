# How to create "Edit" and/or "Display" links in the list row

The simplest way to create "edit" and/or "display" links for elements on list is to use the
[action](https://github.com/fsi-open/datagrid/blob/master/doc/en/columns/action.md) column type.
This type has an `actions` option that renders the defined actions in a cell of each row.
There are some predefined templates for actions like "edit" and "display", which you can find in
the [datagrid template](/Resources/views/CRUD/datagrid.html.twig).

Bellow is an example configuration of two actions, editing and displaying:

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_users.yaml

columns:
  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      actions:
        edit:
          route_name: "fsi_admin_form"
          additional_parameters: { element: admin_users_form }
          parameters_field_mapping: { id: id }
        display:
          route_name: "fsi_admin_display"
          additional_parameters: { element: admin_users_display }
          parameters_field_mapping: { id: id }
```

Instead of setting this every single time, you can use the ``element`` option of each action,
which is a shortcut for creating an action pointing to that element. Following configuration
works exactly like the one above:

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_users.yaml

columns:
  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      actions:
        edit:
          element: admin_users_form
        display:
          element: admin_users_display
```

*You should not use this shortcut option if the entity that is edited or displayed by the admin element
is identified by any other field than `id`*

[Back to admin element configuration](admin_element_crud.md)
