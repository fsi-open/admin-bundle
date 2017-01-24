<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Symfony\Form\EventSubscriber;

use FSi\Component\DataSource\Field\FieldViewInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DataSourceEvents;
use FSi\Component\DataSource\Event\DataSourceEvent;

/**
 * Class contains method called during DataSource events.
 */
class Events implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            DataSourceEvents::POST_BUILD_VIEW => ['postBuildView'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function postBuildView(DataSourceEvent\ViewEventArgs $event)
    {
        $fieldViews = $event->getView()->getFields();

        $positive = [];
        $negative = [];
        $neutral = [];

        $indexedViews = [];
        foreach ($fieldViews as $fieldView) {
            $field = $event->getDataSource()->getField($fieldView->getName());
            if ($field->hasOption('form_order')) {
                if (($order = $field->getOption('form_order')) >= 0) {
                    $positive[$field->getName()] = $order;
                } else {
                    $negative[$field->getName()] = $order;
                }
                $indexedViews[$field->getName()] = $fieldView;
            } else {
                $neutral[] = $fieldView;
            }
        }
        asort($positive);
        asort($negative);

        $fieldViews = [];
        foreach ($negative as $name => $order) {
            $fieldViews[] = $indexedViews[$name];
        }

        $fieldViews = array_merge($fieldViews, $neutral);
        foreach ($positive as $name => $order) {
            $fieldViews[] = $indexedViews[$name];
        }

        $event->getView()->setFields($fieldViews);
    }
}
