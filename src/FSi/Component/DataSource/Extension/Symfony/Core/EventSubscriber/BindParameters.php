<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Symfony\Core\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DataSourceEvents;
use FSi\Component\DataSource\Event\DataSourceEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class contains method called at BindParameters events.
 */
class BindParameters implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [DataSourceEvents::PRE_BIND_PARAMETERS => ['preBindParameters', 1024]];
    }

    /**
     * Method called at PreBindParameters event.
     *
     * @param \FSi\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs $event
     */
    public function preBindParameters(DataSourceEvent\ParametersEventArgs $event)
    {
        $parameters = $event->getParameters();
        if ($parameters instanceof Request) {
            $event->setParameters($parameters->query->all());
        }
    }
}
