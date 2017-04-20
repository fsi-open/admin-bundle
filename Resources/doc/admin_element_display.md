# Display element

Display component, as the name itself suggests, is used for displaying data.
You will need to prepare a [Display](Display/Display) object, which will return
an array of [Property](Display/Property) objects (these have a value and a label).
There is a [default template](Resources/views/Display/display.html.twig) for the
element, but since it is pretty basic, we encourage you to create one yourself.

Without further ado, below are all the steps required to create a proper display
element.

## 1. Prepare display data

Display data is passed into the view via a class implementing the [Display](Display/Display)
interface. Currently there are two classes available for your use, but you can
easily create a new one. Just remember to implement the aforementioned interface.

### Using SimpleDisplay class

If you want to display data, which is not tied to a simple object, you can use
the [SimpleDisplay](Display/SimpleDisplay) class. Just add values with respective labels,
wihout the need for an underlying object:

```php

use FSi\Bundle\AdminBundle\Display\Property;
use FSi\Bundle\AdminBundle\Display\SimpleDisplay;

$display = new SimpleDisplay();
$display->add('value', 'String value');
$display->add(1, 'Number value');

/* @var Property[] $data */
$data = $display->getData();
```

### Using PropertyAccessDisplay class

Alternatively, you can use [PropertyAccessDisplay](Display/PropertyAccessDisplay) class.
Instead of supplying values, you define property paths to be read from the object
or array used in the creation of the `PropertyAccessDisplay` instance.

```php

namespace FSi\FixturesBundle\Entity;

use DateTime;

class News
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTime
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDate()
    {
        return $this->date;
    }
}
```

```php

use FSi\Bundle\AdminBundle\Display\Property;
use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\FixturesBundle\Entity\News;

$display = new PropertyAccessDisplay();
$display->add('id', 'Identifier');
$display->add('title', 'Title');

/* @var Property[] $data */
$data = $display->getData();
```

### Using value formatters

If you would like the values to be formatted before displaying, you can add an
array of formatters as the third parameter. They will be executed in order they
are specified.

```php

use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\FixturesBundle\Entity\News;

$display = new PropertyAccessDisplay();
$display->add('id', 'Identifier');
$display->add('title', 'Title');
$display->add('date', 'Date', [
    new Formatter\EmptyValue(),
    new Formatter\DateTime('Y-m-d H:i:s')
]);
```

You can of course create your own formatters, just remember that they need to 
implement the [ValueFormatter](Display/Property/ValueFormatter) interface.

For the full list of available formatters, go [here](Display/Property/Formatter).

## 2. Create an admin element class

Now that you know how to prepare your data, you will need to create the element
itself:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/DisplayNewsElement

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;
use FSi\FixturesBundle\Entity\News;

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
     * @param News $object
     * @return Display
     */
    protected function initDisplay($object)
    {
        $display = new PropertyAccessDisplay($object);
        $display->add('id', 'Identity')
            ->add('title', 'Title')
            ->add('date', 'Date', array(
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ))
            ->add('visible', 'Visible', array(
                new Formatter\Boolean("yes", "no")
            ))
            ->add('categories', 'Categories')
            ->add('createdAt', 'Created at', array(
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ))
            ->add('creatorEmail', 'Creator email');

        return $display;
    }
}
```

## 3. Add display action to news datagrid definition

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/news.yml

columns:

  # other columns

  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      display:
        element: news-display
```


Remember to use id of element that is returned by ``DisplayNewsElement::getId`` method.

### Admin element options

As with any other element, you can set options by overriding the `setDefaultOptions`
method. Currently, only `template` option is available.

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
