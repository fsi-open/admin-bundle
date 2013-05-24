#Events 

Admin bundle provide several events that can handled in application. 

List of available events can be found in [AdminEvents](Event/AdminEvents.php)

## Event listener registration

First you need to create EventListener class. 

```php

namespace FSi\Bundle\DemoBundle\EventListener;

use FSi\Bundle\AdminBundle\Admin\Article;
use FSi\Bundle\AdminBundle\Event\AdminEvent;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CRUDEventListener
{
    public function crudEditFormRequestPreBind(AdminEvent $event)
    {
        $request = $event->getRequest();
        $context = $event->getContext(); 
        $element = $context->getElement(); 

        if ($element instanceof Article) { 
          // Do whatever you need with context and admin element. 
        }
    }
}

```

Next and last step is event listener registration

```
<!-- src/FSi/Bundle/DemoBundle/Resources/config/services.xml --> 

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- Event Listeners -->
        <service id="admin.listener.crud" class="FSi\Bundle\CompanySiteBundle\EventListener\CRUDEventListener">
            <tag name="kernel.event_listener" event="admin.crud.create.form.request.pre_bind" method="crudEditFormRequestPreBind" />
        </service>
    </services>
</container>
```
