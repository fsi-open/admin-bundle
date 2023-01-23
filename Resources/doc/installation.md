# Installation in 4 simple steps

## 1. Add FSiAdminBundle to your project

Add the following line to your `composer.json` and run update:

```json
"require": {
    "fsi/admin-bundle": "^4.0@dev"
}
```

## 2. Register required bundles

Update your `AppKernel.php` file with the following bundles:

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

## 3. Import the routing configuraton

```yaml
# config/routes.yaml

admin:
    resource: "@FSiAdminBundle/Resources/config/routing/admin.yaml"
    prefix: /admin
```

## 4. Enable translations

```yaml
# config/framework.yaml

framework:
    translator:      { fallback: "%locale%" }
```

Congratulations! Now you can register your first [admin element](admin_element.md).

[Back to index](index.md)
