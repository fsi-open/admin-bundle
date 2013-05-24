<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Context\ContextInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminEvent extends Event
{
    /**
     * @var \FSi\Bundle\AdminBundle\Context\ContextInterface
     */
    protected $context;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param ContextInterface $context
     * @param Request $request
     */
    public function __construct(ContextInterface $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Context\ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
