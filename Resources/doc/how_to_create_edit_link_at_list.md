# How to create "Edit" and/or "Display" links at list

So the simplest way to create "edit" and/or "display" link from list of elements is to use
[action](https://github.com/fsi-open/datagrid/blob/master/doc/en/columns/action.md) column type.
Action column type have "actions" option that takes actions displayed in cell.
There are some predefined templates for actions like "edit" and "display", you can find them in
[datagrid template](/Resources/views/CRUD/datagrid.html.twig)

Following configuration of action column will add edit and display actions into it.

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_users.yml

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

To prevent setting this every single time you can use ``admin_edit_element_id`` and ``admin_display_element_id`` options
which are shortcuts to create these actions. So following configuration works exactly like above one

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_users.yml

columns:
  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      admin_edit_element_id: admin_users_form
      admin_display_element_id: admin_users_display
```

*You should not use this shortcut option if entity that is edited or displayed by admin element is identified by other
field than "id"*

[Back to admin element configuration](admin_element_crud.md)
