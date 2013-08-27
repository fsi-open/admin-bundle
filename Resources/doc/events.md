#Events 

Admin bundle provide several events that can be handled in application.
List of available events can be found in [AdminEvents](Event/AdminEvents.php)
Following example will show you how to handle dynamically added relation elements for doctrine entity.
Just like in http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-tags-to-be-removed

First you need to create event event listener

```php

<?php

namespace FSi\Bundle\DemoBundle\EventListener;

use FSi\Bundle\DemoBundle\Entity\News;
use FSi\Bundle\DemoBundle\Entity\Tag;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class CRUDEventListener
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    protected $registry;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->tags = array();
        $this->registry = $registry;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     */
    public function crudEditEntityPreBind(AdminEvent $event)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement */
        $element = $event->getElement();
        $entity = $element->getEditForm()->getData();

        if ($entity instanceof News) {
            $this->tags[$entity->getId()] = array();

            foreach ($entity->getTags() as $tag) {
                $this->tags[$entity->getId()][] = $tag;
            }
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     */
    public function crudEditEntityPostSave(AdminEvent $event)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement */
        $element = $event->getElement();
        $entity = $element->getEditForm()->getData();

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

Next and last step is event listener registration

```
<!-- src/FSi/Bundle/DemoBundle/Resources/config/services.xml --> 

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- Event Listeners -->
        <service id="fsi_demo_bundle.admin.listener.crud" class="FSi\Bundle\DemoBundle\EventListener\CRUDEventListener">
            <argument type="service" id="doctrine" />
            <tag name="kernel.event_listener" event="admin.crud.edit.form.request.pre_bind" method="crudEditEntityPreBind" />
            <tag name="kernel.event_listener" event="admin.crud.edit.entity.post_save" method="crudEditEntityPostSave" />
        </service>
    </services>
</container>
```
