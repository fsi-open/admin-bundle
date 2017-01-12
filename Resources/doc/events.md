# Events

Admin bundle provides several events that can be listened for. Their full list,
along with their argument types, can be found in the following classes:

- [AdminEvents](/Event/AdminEvents.php), type of argument: [AdminEvent](/Event/AdminEvent.php)
- [CRUDEvents](/Event/CRUDEvents.php), type of argument: [ListEvent](/Event/ListEvent.php) for ``CRUD_LIST_*`` and [FormEvent](/Event/FormEvent.php) for all the others
- [ListEvents](/Event/ListEvents.php), type of argument: [ListEvent](/Event/ListEvent.php)
- [FormEvents](/Event/FormEvents.php), type of argument: [FormEvent](/Event/FormEvent.php)
- [DisplayEvents](/Event/DisplayEvents.php), type of argument: [DisplayEvent](/Event/DisplayEvent.php)
- [BatchEvents](/Event/BatchEvents.php), type of argument: [FormEvent](/Event/FormEvent.php)
- [ResourceEvents](/Event/ResourceEvents.php), type of argument: [FormEvent](/Event/FormEvent.php)

Following example will show you how to handle dynamically added/removed relation elements for a Doctrine entity,
similarly to http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-tags-to-be-removed

> This example is only a proof of concept - in this you should use the `orphanRemoval` Doctrine
> relation option instead of a custom event listener.

For starters, we create an event listener class:

```php

<?php

namespace FSi\Bundle\DemoBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\DemoBundle\Entity\News;
use FSi\Bundle\DemoBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class CRUDEventListener
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    private $registry;

    /**
     * @var array
     */
    private $tags;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->tags = array();
        $this->registry = $registry;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    public function crudEditEntityPreSubmit(FormEvent $event)
    {
        $entity = $event->getForm()->getData();

        if ($entity instanceof News) {
            $this->tags[$entity->getId()] = array();

            foreach ($entity->getTags() as $tag) {
                $this->tags[$entity->getId()][] = $tag;
            }
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    public function crudEditEntityPostSave(FormEvent $event)
    {
        $entity = $event->getForm()->getData();

        if ($entity instanceof News) {
            foreach ($entity->getTags() as $tag) {
                foreach ($this->tags[$entity->getId()] as $key => $toDel) {
                    if ($toDel->getId() === $tag->getId()) {
                        unset($this->tags[$entity->getId()][$key]);
                    }
                }
            }

            foreach ($this->tags[$entity->getId()] as $tag) {
                $this->registry->getManager()->remove($tag);
                $this->registry->getManager()->flush();
            }
        }
    }
}

```

Then we register the event listener as a service, adding relevant tags for each
event we want to listen for:

```xml
<!-- src/FSi/Bundle/DemoBundle/Resources/config/services.xml -->

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- Event Listeners -->
        <service id="fsi_demo_bundle.admin.listener.crud" class="FSi\Bundle\DemoBundle\EventListener\CRUDEventListener">
            <argument type="service" id="doctrine" />
            <tag name="kernel.event_listener" event="admin.crud.edit.form.request.pre_submit" method="crudEditEntityPreSubmit" />
            <tag name="kernel.event_listener" event="admin.crud.edit.entity.post_save" method="crudEditEntityPostSave" />
        </service>
    </services>
</container>
```

You can of course use a subscriber and move the event configuration inside the class
itself, the choice is entirely up to you.

[Back to index](index.md)
