<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\EventSubscriber;

use FSi\Component\DataGrid\DataGridEventInterface;
use FSi\Component\DataGrid\DataGridEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ColumnOrder implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [DataGridEvents::POST_BUILD_VIEW => ['postBuildView', 128]];
    }

    /**
     * {@inheritdoc}
     */
    public function postBuildView(DataGridEventInterface $event)
    {
        $view = $event->getData();
        $columns = $view->getColumns();

        if (count($columns)) {
            $positive = [];
            $negative = [];
            $neutral = [];

            $indexedColumns = [];
            foreach ($columns as $column) {
                if ($column->hasAttribute('display_order')) {
                    if (($order = $column->getAttribute('display_order')) >= 0) {
                        $positive[$column->getName()] = $order;
                    } else {
                        $negative[$column->getName()] = $order;
                    }
                    $indexedColumns[$column->getName()] = $column;
                } else {
                    $neutral[] = $column;
                }
            }

            asort($positive);
            asort($negative);

            $columns = [];
            foreach ($negative as $name => $order) {
                $columns[] = $indexedColumns[$name];
            }

            $columns = array_merge($columns, $neutral);
            foreach ($positive as $name => $order) {
                $columns[] = $indexedColumns[$name];
            }

            $view->setColumns($columns);
        }
    }
}
