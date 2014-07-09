# How to create simple form element in 2 simple steps

## 1. Create admin form element class which builds your form

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/SubscriberFormElement

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class SubscriberFormElement extends FormElement
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
        return 'subscriber_form'; // id is used in url generation http://domain.com/admin/form/{id}
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder('subscriber', 'form', $data, array(
            'data_class' => $this->getClassName()
        ));

        $builder->add('email', 'email', array(
            'label' => 'admin.subscriber.list.email',
        ));
        $builder->add('created_at', 'date', array(
            'label' => 'admin.subscriber.list.created_at',
            'widget' => 'single_text'
        ));
        $builder->add('active', 'checkbox', array(
            'label' => 'admin.subscriber.list.active',
        ));

        return $builder->getForm();
    }
}
```

[Symfony Form building guide](http://symfony.com/doc/current/book/forms.html#building-the-form)

## 2. Add your form element to some list element

Assume you already have some list element defined like [here](admin_element_list.md).
You can easily attach your new form element into datagrid actions column:
 
```
# src/FSi/Bundle/DemoBundle/Resources/config/datasource/admin_subscribers.yml

columns:
  # ... other columns
  actions:
    type: action
    field_mapping: [ id ]
    actions:
      edit:
        route_name: fsi_admin_form
        additional_parameters:
          element: subscriber_form # here's what SubscriberFormElement::getId() returns
        parameters_field_mapping:
          id: id
```

You can read more about configuration of action datagrid column in [DataGrid documentation](https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/columns/action.md)

[Back to index](index.md)
