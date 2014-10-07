# How to create admin display element in 2 steps

Display element is used to display single object of specific type at page.
For example invoices. It's good to have list of invoices but some parts of data are just too big
to display them at list. Like customer address od full list of products. That's why there is a Display object
that allows you to show all interesting data without using form with "disabled" attributes.

## 1. Create admin element class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/DisplayNewsElement

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\ObjectDisplay;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Display\DisplayElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class DisplayNewsElement extends DisplayElement
{
    const ID = 'news-display';

    /**
     * @return string
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    /**
     * @param mixed $object
     * @return Display
     */
    protected function initDisplay($object)
    {
        $display = new ObjectDisplay($object);
        $display->add('id', 'Identity')
            ->add('title')
            ->add('date', null, array(
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ))
            ->add('visible', 'Visible', array(
                new Formatter\Boolean("yes", "no")
            ))
            ->add('categories')
            ->add('createdAt', null, array(
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ))
            ->add('creatorEmail');

        return $display;
    }
}
```

## 2. Add display action into news list

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/news.yml

columns:

  # other columns

  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      admin_display_element_id: news-display
```


Remember to use id of element that is returned by ``DisplayNewsElement::getId`` method.

## Admin element options

There are also several options that you can use to configure admin element.
This can be easily done by overwriting ``setDefaultOptions`` method in admin element class.
Following example contains all available options with default values:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/DisplayNewsElement

namespace FSi\Bundle\DemoBundle\Admin;

/**
 * IMPORTANT - Without "Element" annotation element will not be registered in admin elements manager!
 *
 * @Admin\Element
 */
class DisplayNewsElement extends DisplayElement
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "template" => "@FSiAdmin/Display/display.html.twig"
        ));
    }
}
```

[Back to index](index.md)
