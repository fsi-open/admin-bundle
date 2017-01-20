# Adding new resource type

Adding new resource type is very easy at all.
First you need to create resource type class that implements ``ResourceInterface`` or extends ``AbstractType``

```php
<?php

namespace FSi\Bundle\DemoBundle\Repository\Resource\Type;

use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailType extends AbstractType
{
    // This will be added later
}
```

Ok now you need to decide witch field in database will be used to store data.
Here is a list of choices you have:

- textValue
- datetimeValue
- dateValue
- timeValue
- numberValue
- integerValue
- boolValue

For EmailType the best choice is probably ``textValue``

```php
<?php

namespace FSi\Bundle\DemoBundle\Repository\Resource\Type;

use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'textValue';
    }
}
```

Now we need to decide form type that will be used to modify resource value. Remember that this must be a valid symfony2
form type.

```php
<?php

namespace FSi\Bundle\DemoBundle\Repository\Resource\Type;

use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'textValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'email';
    }
}
```

Now the last thing is to create service with ``resource.type`` tag and ``email`` alias.

```
    <service id="fsi_demo_bundle.resource.type.url" class="FSi\Bundle\DemoBundle\Repository\Resource\Type\EmailType">
        <tag name="resource.type" alias="email"/>
    </service>
```

From now we have ``email`` resource type that can be used in resource map.
