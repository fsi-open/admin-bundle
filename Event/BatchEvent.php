<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

abstract class BatchEvent extends Event
{
    private Element $element;

    private Request $request;

    /**
     * @var array<string,mixed>|object
     */
    private $object;

    /**
     * @param Element $element
     * @param Request $request
     * @param array<string,mixed>|object $object
     */
    public function __construct(Element $element, Request $request, $object)
    {
        $this->element = $element;
        $this->request = $request;
        $this->object = $object;
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return array<string,mixed>|object
     */
    public function getObject()
    {
        return $this->object;
    }
}
