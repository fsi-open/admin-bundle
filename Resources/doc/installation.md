# Installation in 3 simple steps

## 1. Download Admin Bundle

Add to composer.json

```
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
        /* Resource Repository */
        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),

        /* CRUD */
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
    );
}
```

## 3. Set route path to AdminController

```
# app/config/routing.yml

admin:
    resource: "@FSiAdminBundle/Resources/config/routing/admin.yml"
    prefix: /admin
```

Now you should read something about [admin elements](Resources/doc/admin_element.md)