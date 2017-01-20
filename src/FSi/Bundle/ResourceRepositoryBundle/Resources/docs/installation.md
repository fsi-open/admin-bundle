# Installation

## 1. Composer
Add to composer.json following lines

```
"require": {
    "doctrine/doctrine-bundle": "dev-master",
    "doctrine/doctrine-cache-bundle": "dev-master"
    "fsi/resource-repository-bundle": "~1.1"
}
```

## 2. Application Kernel

Register bundle in AppKernel

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()
        // ...
    );

    return $bundles;
}
```

### 3. Create entity

Create entity that extends base resource model

```php
<?php

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\ResourceRepositoryBundle\Model\Resource as BaseResource;

/**
 * @ORM\Entity(repositoryClass="FSi\Bundle\ResourceRepositoryBundle\Doctrine\ResourceRepository")
 * @ORM\Table(name="fsi_resource")
 */
class Resource extends BaseResource
{
}
```

### 4. Set resource class in application config

```
# app/config/config.yml

fsi_resource_repository:
    db_driver: orm
    resource_class: FSi\Bundle\DemoBundle\Entity\Resource
```

**Heads up!** Although ``db_driver`` option has its default value ``orm``, you should put it in your
config file to prevent problems with future releases of fsi/resource-repository-bundle.

### 5. Update db schema

Update your database schema

```
$ php app/console doctrine:schema:update --force
```

Now you are ready to create [Resource Map](resource_map.md)
