# How to create a batch action element in 2 steps

## 1. Create the element's class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberDeactivateElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

class SubscriberDeactivateElement extends BatchElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Subscriber'; // Doctrine entity's class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'subscribers_deactivate'; // ID is used in url generation http://domain.com/admin/batch/{id}
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object)
    {
        $object->setActive(false); // this is where the element logic is being applied
        $this->getObjectManager()->flush();
    }
}
```

## 2. Add batch column to subscribers grid definition

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_subscribers.yml

columns:
  batch:
    type: batch
    options:
      actions:
        deactivate:
          element: subscribers_deactivate
          label: deactivate

  # other columns

```

## 3. Doctrine delete admin element

This is the only predefined batch admin element class, which can be used to delete entities/documents in batch mode.

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberDeactivateElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DeleteElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

class SubscriberDeleteElement extends DeleteElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Subscriber'; // Doctrine entity's class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'subscribers_delete'; // id is used in url generation http://domain.com/admin/batch/{id}
    }
}
```

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_subscribers.yml

columns:
  batch:
    type: batch
    options:
      actions:
        delete:
          element: subscribers_delete
          label: delete

  # other columns

```

[Back to index](index.md)
