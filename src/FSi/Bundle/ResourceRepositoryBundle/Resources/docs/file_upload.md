# File Upload

To use file as a resource type you need to register [FSiDoctrineExtensionsBundle](https://github.com/fsi-open/doctrine-extensions-bundle)
in your application. Following instruction will help you in this.

## 1. Composer
Add to composer.json following lines

```
"require": {
    "fsi/doctrine-extensions-bundle" : "1.0.*"
}
```

## 2. Application Kernel

Register bundle in AppKernel  
**IMPORTANT!!** make sure that ``FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle()`` is registered
**before** ``FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()``. In other way you will not be able
to use file resource type.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(), // required by FSiDoctrineExtensionsBundle
        new FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle(),

        // FSiResourceRepositoryBundle must be after FSiDoctrineExtensionsBundle

        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()
    );

    return $bundles;
}
```

## 3. Modify resource entity

Now you need to change BaseResource class in your [Resource Entity](installation.md#3-create-entity) from
``FSi\Bundle\ResourceRepositoryBundle\Model\Resource`` to ``FSi\Bundle\ResourceRepositoryBundle\Model\ResourceFSiFile``

This is how your Resource class should look now
```php
<?php

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceFSiFile as BaseResource;

/**
 * @ORM\Entity(repositoryClass="FSi\Bundle\ResourceRepositoryBundle\Doctrine\ResourceRepository")
 * @ORM\Table(name="fsi_resource")
 */
class Resource extends BaseResource
{
}
```

## 4. Update db schema

Update your database schema

```
$ php app/console doctrine:schema:update --force
```

And now you should be able to use new resource types in your [Resource Map](resource_map.md). These types are:

- ``fsi_file`` - any file uploaded through uploadable doctrine extension
- ``fsi_image`` - only images uploaded through uploadable doctrine extension
- ``fsi_removable_file`` - one of the above with additional option to delete uploaded files

Example:

```yaml
# app/config/resource_map.yml

resources:
    type: group
    home_page:
        type: group
        header:
            type: fsi_image
            form_options:
                label: Header background
            constraints:
                FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints\Image:
                    maxWidth: 1650
                    maxHeight: 600
        advertisement:
            type: fsi_removable_file
            form_options:
                label: Advertisement
                file_type: fsi_image
                file_options:
                    constraints:
                        FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints\Image:
                            maxWidth: 400
                            maxHeight: 500
```
