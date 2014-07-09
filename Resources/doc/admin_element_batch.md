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

## 2. Modify your list admin element and set custom list template

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberElement

// ...

class SubscriberElement extends ListElement
{
    // ...

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array('template_list' => 'Admin/subscriber_list.html.twig')); 
    }
}

```
{# src/FSi/Bundle/DemoBundle/Resources/views/Admin/subscriber_list.html.twig #}
{% extends '@FSiAdmin/List/list.html.twig' %}

{% block batch_actions %}
    <option value="{{ path('fsi_admin_batch', {element : 'subscriber_deactivate'}) }}">{{ 'deactivate'|trans }}</option>
{% endblock batch_actions %}
```

[Back to index](index.md)
