<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event\DriverEvent;

use Symfony\Component\EventDispatcher\Event;
use FSi\Component\DataSource\Driver\DriverInterface;

/**
 * Event class for Driver.
 */
class DriverEventArgs extends Event
{
    /**
     * @var \FSi\Component\DataSource\Driver\DriverInterface
     */
    private $driver;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param \FSi\Component\DataSource\Driver\DriverInterface $driver
     * @param array $fields
     */
    public function __construct(DriverInterface $driver, array $fields)
    {
        $this->driver = $driver;
        $this->fields = $fields;
    }

    /**
     * @return \FSi\Component\DataSource\Driver\DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
