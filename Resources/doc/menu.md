# Admin panel menu

Menu is displayed in the upper part of admin panel, on the black navigation bar.
By default menu is empty and you should configure it in ``app/config/admin_menu.yml`` file

```
# app/config/admin_menu.yml

menu:
  - news
  - Structure:
    - home_page
    - about_us_page
```

About menu will display link to admin element with id "news" and dropdown button that
have links to elements with "home_page" and "about_us_page" id.

## Translating groups

Group names are translated so you can also use translations key:

```
# app/config/admin_menu.yml

menu:
  - news
  - admin.page.structure:
    - home_page
    - about_us_page
```

```
# app/Resources/translations/messages.en.yml

admin:
  page:
    structure: Structure
```

[Back to index](index.md)
