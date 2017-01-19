<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface HandlerInterface
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request);
}