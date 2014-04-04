# How to create simple resource element in 4 steps

## 1. Installation

**This element type require to install fsi/resource-repository-bundle before using it.**
**You can read more about it [here](https://github.com/fsi-open/resource-repository-bundle)**
**This step should be done only once**

Add to your composer.json following lines

```
"require": {
    "doctrine/doctrine-bundle" : "1.2.*@dev"
    "fsi/resource-repository-bundle" : "1.0.*"
}
```

Update AppKernel.php

```php
public function registerBundles()
{
    $bundles = array(
        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
        // Admin Bundle
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
    );
}
```

Create Resource entity 

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

Modify app/config/config.yml

```
# app/config/config.yml

fsi_resource_repository:
    resource_class: FSi\Bundle\DemoBundle\Entity\Resource
```

Update database with following console command 

```
$ php app/console doctrine:schema:update --force
```

## 2. Resources configuration

Lets assume we have following configuration in ``resource_map.yml``

```yml
resources:
    type: group
    main_page:
        type: group
        content:
            type: textarea
            form_options:
                label: Main page content
```

## 3. Create admin resource element class

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
        return 'resources.main_page'; // must be a group type key
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
    public function getName()
    {
        return 'Main Page';
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

## 4. Add element into menu

By default elements are not visible in menu. You need to add it into menu manually.

```
# app/config/admin_menu.yml

menu:
  - main_page
```

## Admin element options

There are also several options that you can use to configure admin element.
This can be easily done by overwriting ``setDefaultOptions`` method in admin element class.
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
            "title" => "resource.title",
            "template" => "@FSiDemo/Resource/resource.html.twig",
        ));
    }
}
```

[Back to index](index.md)
