<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListDataSourcePostBindEvent;
use FSi\Bundle\AdminBundle\Event\ListDataSourcePreBindEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function get_class;
use function sprintf;

class DataSourceBindParametersHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s requires ListEvent', get_class($this)));
        }

        $dataSourcePreBindEvent = ListDataSourcePreBindEvent::fromOtherEvent($event);
        $this->eventDispatcher->dispatch($dataSourcePreBindEvent);
        $response = $dataSourcePreBindEvent->getResponse();
        if (null !== $response) {
            return $response;
        }

        $event->getDataSource()->bindParameters($request);

        $dataSourcePostBindEvent = ListDataSourcePostBindEvent::fromOtherEvent($event);
        $this->eventDispatcher->dispatch($dataSourcePostBindEvent);

        return $dataSourcePostBindEvent->getResponse();
    }
}
