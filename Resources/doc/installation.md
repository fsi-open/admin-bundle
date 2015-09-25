# Installation in 4 simple steps

## 1. Download Admin Bundle

Add to composer.json

```json
"require": {
    "fsi/admin-bundle": "1.0.*"
}
```

## 2. Register bundles

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
    );
}
```

## 3. Set route path to AdminController

```yaml
# app/config/routing.yml

admin:
    resource: "@FSiAdminBundle/Resources/config/routing/admin.yml"
    prefix: /admin
```

## 4. Enable translations

```yaml
# app/config/config.yml

framework:
    translator:      { fallback: %locale% }
```

It's time to register your first [admin element](admin_element.md)

[Back to index](index.md)
