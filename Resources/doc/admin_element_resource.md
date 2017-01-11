# How to create a simple resource element in 4 steps

## 1. Installation

**Before using Resource elements you need to install [fsi/resource-repository-bundle](https://github.com/fsi-open/resource-repository-bundle).**
**This step should only be done once**

### Add the following line to your composer.json and run update:

```json
"require": {
    "fsi/resource-repository-bundle": "^1.1"
}
```

### Update the AppKernel.php file, adding the `FSiResourceRepositoryBundle`:

```php
public function registerBundles()
{
    $bundles = array(
        # Add this line
        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
        // Admin Bundle
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
    );
}
```

### Create a Resource entity class

```php
<?php

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\ResourceRepositoryBundle\Model\Resource as BaseResource;

/**
 * @ORM\Entity(repositoryClass="FSi\Bundle\ResourceRepositoryBundle\Entity\ResourceRepository")
 * @ORM\Table(name="fsi_resource")
 */
class Resource extends BaseResource
{
}
```

### Modify app/config/config.yml

```yaml
# app/config/config.yml

fsi_resource_repository:
    resource_class: FSi\Bundle\DemoBundle\Entity\Resource
```

### Update your database schema

```sh
$ php app/console doctrine:schema:update --force
```

## 2. Resources configuration

Let's assume we have the following configuration in ``resource_map.yml``

```yml
# app/config/resource_map.yml

resources:
    type: group
    main_page:
        type: group
        content:
            type: textarea
            form_options:
                label: Main page content
```

This will define a `resources` group with a single key `main_page`.

## 3. Create a resource element class

```php
<?php

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class MainPage extends ResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'resources.main_page'; // must be a group type key, defined in the `resource_map.yml`
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}
```

## 4. Add element to the main menu

By default elements are not visible in menu. You need to add them manually in the `admin_menu.yml` file:

```yaml
# app/config/admin_menu.yml

menu:
  - main_page # This will use the element's ID as the label
  # This will add an element with ID "main_page" and label "Main Page" - you can also
  # use translation keys as names
  - { "id": main_page, "name": "Main Page" }

```

## Admin element options

There are also several options that you can use to configure the element.
This can be easily done by overwriting ``setDefaultOptions`` method in the element's class.
Following example contains all available options with default values:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace FSi\Bundle\DemoBundle\Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class UserElement extends CRUDElement
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "template" => "@FSiDemo/Resource/resource.html.twig",
        ));
    }
}
```

[Back to index](index.md)
