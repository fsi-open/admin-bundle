<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\DataSourceAbstractExtension;
use FSi\Component\DataSource\Event\DataSourceEvents;

/**
 * Class to test DataSource extensions calls.
 */
class DataSourceExtension extends DataSourceAbstractExtension implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $calls = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            DataSourceEvents::PRE_BIND_PARAMETERS => ['preBindParameters', 128],
            DataSourceEvents::POST_BIND_PARAMETERS => ['postBindParameters', 128],
            DataSourceEvents::PRE_GET_RESULT => ['preGetResult', 128],
            DataSourceEvents::POST_GET_RESULT => ['postGetResult', 128],
            DataSourceEvents::PRE_BUILD_VIEW => ['preBuildView', 128],
            DataSourceEvents::POST_BUILD_VIEW => ['postBuildView', 128],
            DataSourceEvents::PRE_GET_PARAMETERS => ['preGetParameters', 128],
            DataSourceEvents::POST_GET_PARAMETERS => ['postGetParameters', 128],
        ];
    }

    /**
     * Returns array of calls.
     *
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Resets calls.
     */
    public function resetCalls()
    {
        $this->calls = [];
    }

    /**
     * Catches called method.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $this->calls[] = $name;
    }

    /**
     * Loads itself as subscriber.
     *
     * @return array
     */
    public function loadSubscribers()
    {
        return [$this];
    }
}
