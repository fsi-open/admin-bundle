<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Context\Request;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
