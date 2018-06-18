# Admin panel menu

By default, the menu is displayed on the top navigation bar and has no elements.
You can add these through the ``app/config/admin_menu.yml`` file:

```yaml
# app/config/admin_menu.yml

menu:
  - news
  - { "id": files, "name": "Files element" }
```

This will add two elements, one identified by `news` and with label `news`, the
other identified by `files` and with the `"Files element"` label. We recommend using
the latter, more verbose method.

## Translating groups

Group names are translated, so you can also use translations keys:

```yaml
# app/config/admin_menu.yml

menu:
  - { "id": news, "name": admin.menu.news }
  # "admin.menu.files" will only exists as a non-clickable root for the submenu,
  # containing two elements
  - admin.menu.files:
    - { "id": public_files, "name": admin.menu.files.public }
    - { "id": private_files, "name": admin.menu.files.private }
```

```yaml
# app/Resources/translations/messages.en.yml

admin:
  menu:
    news: News
    files:
      public: Public files
      private: Private files
```

## Custom controllers

The menu can also contain links to actions with a custom controller, for example:

```yaml
# app/config/admin_menu.yml

menu:
  - name: admin.menu.news
    route: custom_route_name
    route_parameters:
      foo: foo_value
      bar: bar_value
```

# Troubleshooting

If you add a position to the ``app/config/admin_menu.yml`` and it is not being
displayed, make sure you've given the proper ID and the element is actually registered.
All values which do not correspond to IDs of properly registered elements are removed during
the creation of the menu - with the exception of roots of submenus.

[Back to index](index.md)
