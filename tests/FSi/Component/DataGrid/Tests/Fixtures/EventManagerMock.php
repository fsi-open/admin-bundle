<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures;

class EventManagerMock
{
    protected $listeners;

    public function __construct($listeners)
    {
        $this->listeners = $listeners;
    }

    public function getListeners()
    {
        return [$this->listeners];
    }
}
