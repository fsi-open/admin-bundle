<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractListRequestHandler implements HandlerInterface
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

    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf("%s require ListEvent", get_class($this)));
        }
    }
}