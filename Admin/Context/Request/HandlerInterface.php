<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response;
}
