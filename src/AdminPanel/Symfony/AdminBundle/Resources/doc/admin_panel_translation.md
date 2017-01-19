## Admin panel translation

Currently the bundle is available in two languages:
- english (en),
- polish (pl),

but you can easily add another language by creating a proper translation file.

To enable the language switcher in the main menu, you need to add following lines
to the ``app/config/config.yml`` file:

```yaml
# app/config/config.yml

fsi_admin:
    display_language_switch: true
```

[Back to index](index.md)
