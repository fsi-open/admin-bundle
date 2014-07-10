# How to create batch action element in 2 steps

## 1. Create admin batch element class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberDeactivateElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class SubscriberDeactivateElement extends BatchElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Subscriber'; // Doctrine class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'subscribers_deactivate'; // id is used in url generation http://domain.com/admin/batch/{id}
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object)
    {
        $object->setActive(false);
        $this->getObjectManager()->flush();
    }
}
```

## 2. Add batch column into subscribers list

```
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

[Back to index](index.md)
