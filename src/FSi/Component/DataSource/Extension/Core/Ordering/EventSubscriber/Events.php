<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Core\Ordering\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DataSourceEvents;
use FSi\Component\DataSource\Event\DataSourceEvent;
use FSi\Component\DataSource\Exception\DataSourceException;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use FSi\Component\DataSource\Field\FieldTypeInterface;

/**
 * Class contains method called during DataSource events.
 */
class Events implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $ordering = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            DataSourceEvents::PRE_BIND_PARAMETERS => ['preBindParameters'],
            DataSourceEvents::POST_GET_PARAMETERS => ['postGetParameters'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function preBindParameters(DataSourceEvent\ParametersEventArgs $event)
    {
        $datasource = $event->getDataSource();
        $datasource_oid = spl_object_hash($datasource);
        $datasourceName = $datasource->getName();
        $parameters = $event->getParameters();

        if (isset($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT]) && is_array($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT])) {
            $priority = 0;
            foreach ($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT] as $fieldName => $direction) {
                if (!in_array($direction, ['asc', 'desc'])) {
                    throw new DataSourceException(sprintf("Unknown sorting direction %s specified", $direction));
                }
                $field = $datasource->getField($fieldName);
                $fieldExtension = $this->getFieldExtension($field);
                $fieldExtension->setOrdering($field, ['priority' => $priority, 'direction' => $direction]);
                $priority++;
            }
            $this->ordering[$datasource_oid] = $parameters[$datasourceName][OrderingExtension::PARAMETER_SORT];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postGetParameters(DataSourceEvent\ParametersEventArgs $event)
    {
        $datasource = $event->getDataSource();
        $datasource_oid = spl_object_hash($datasource);
        $datasourceName = $datasource->getName();
        $parameters = $event->getParameters();

        if (isset($this->ordering[$datasource_oid])) {
            $parameters[$datasourceName][OrderingExtension::PARAMETER_SORT] = $this->ordering[$datasource_oid];
        }

        $event->setParameters($parameters);
    }

    /**
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @return \FSi\Component\DataSource\Field\FieldExtensionInterface
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    protected function getFieldExtension(FieldTypeInterface $field)
    {
        $extensions = $field->getExtensions();
        foreach ($extensions as $extension) {
            if ($extension instanceof FieldExtension) {
                return $extension;
            }
        }
        throw new DataSourceException('In order to use ' . __CLASS__ . ' there must be FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension registered in all fields');
    }
}
