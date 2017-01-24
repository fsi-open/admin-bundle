<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;

interface HandlerInterface
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request);
}
