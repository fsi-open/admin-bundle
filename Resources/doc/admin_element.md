# Admin elements

The panel is created by defining admin elements. An element is nothing more than an object that allows for
modification and/or display of your website resources.

Currently there are many different types of elements available and you can choose from:

* [Doctrine CRUD (Create Read Update Delete)](admin_element_crud.md)
* [Doctrine List](admin_element_list.md)
* [Positionable Doctrine Lists](admin_positionable.md)
* [Doctrine Tree Lists](admin_tree.md)
* [Doctrine Form](admin_element_form.md)
* [Doctrine Batch/Delete](admin_element_batch.md)
* [Doctrine Resource](admin_element_resource.md)
* [Doctrine Display](admin_element_display.md)
* [Doctrine dependent elements](admin_dependent_elements.md)

Should you wish to extend any of the elements behaviour, the [event system](events.md) is there to suit
your needs.

# Admin elements registration

In order for the admin element to be properly recognized by the bundle, you need to register it in the
admin element manager. There are two ways to do this:

## Service

The first way is to register your admin element as a service with the tag `admin.element`:

#### XML Example:
```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.element.user" class="FSi\Bundle\DemoBundle\Admin\UserElement">
        <tag name="admin.element"/>
    </service>

</services>
</container>
```

#### YML Example:
```yaml
services:
    tc_api.admin.element.admin_user:
        class: FSi\Bundle\DemoBundle\Admin\UserElement
        tags:
            - { name: admin.element }
```

### Autoconfigure

If you are using Symfony 3.3 or higher, you can use autoconfiguration feature which will add `admin.element` tag
automatically:

#### XML Example:

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <prototype namespace="FSi\Bundle\DemoBundle\Admin\" resource="../../src/FSi/Bundle/DemoBundle/Admin/*" autoconfigure="true" />

    </services>
</container>
```

#### YML Example:

```yaml
services:
    _defaults:
        autoconfigure: true
    FSi\Bundle\DemoBundle\Admin\:
        resource: '../../src/FSi/Bundle/DemoBundle/Admin/*'
```

## Annotation

*WARNING* This method of registration is deprecated as of version 3.0 and will be
removed in 4.0. Please refer to the previous example for a quick registration method.

Probably the simplest way is to annotate your element class like so:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace FSi\Bundle\DemoBundle\Admin;

use use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class UserElement extends CRUDElement
{
}
```

This is fast and easy, but currently there is no way to inject any dependencies
using this method. You will either have to register your element as a service or
use interfaces to modify what dependecies are being injected into the element.

# Injecting request stack to admin element

If your element requires the request object, there is an interface which will
provide automatical injection of the request stack service:

```php
namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\RequestStackAware;

/**
 * @Admin\Element
 */
class UserElement extends CRUDElement implements RequestStackAware
{
    private $requestStack;

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
```

This technique is especially useful when you register admin elements through annotations.

[Back to index](index.md)
