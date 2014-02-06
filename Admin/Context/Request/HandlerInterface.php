<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
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