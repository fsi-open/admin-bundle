# Installation in 3 simple steps

## 1. Download Admin Bundle

Add to composer.json

```
"require": {
    "fsi/admin-bundle": "1.0.*"
},
"minimum-stability" : "dev"
```

or if you want to stay with ``"minimum-stability" : "stable"``

```
"require": {
    "doctrine/doctrine-bundle": "1.2.*@dev",
    "fsi/resource-repository-bundle" : "1.0.*@dev",
    "fsi/admin-bundle": "1.0.*@dev"
},
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
        /* Resource Repository
         * This is additional bundle and should be registered only when you want to use resource admin objects
         */
        //new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
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

## 4. Enable translations

```
# app/config/config.yml

framework:
    translator:      { fallback: %locale% }
```

Now you should read something about [admin elements](admin_element.md)
