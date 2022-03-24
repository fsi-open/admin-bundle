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
use FSi\Bundle\AdminBundle\Display\Display;
use Symfony\Component\HttpFoundation\Request;

class DisplayEvent extends AdminEvent
{
    private Display $display;

    /**
     * @var array<string,mixed>|object
     */
    private $data;

    /**
     * @return static
     */
    public static function fromOtherEvent(self $event): self
    {
        return new static($event->getElement(), $event->getRequest(), $event->getDisplay(), $event->getData());
    }

    /**
     * @param Element $element
     * @param Request $request
     * @param Display $display
     * @param array<string,mixed>|object $data
     */
    final public function __construct(Element $element, Request $request, Display $display, $data)
    {
        parent::__construct($element, $request);

        $this->display = $display;
        $this->data = $data;
    }

    public function getDisplay(): Display
    {
        return $this->display;
    }

    /**
     * @return array<string,mixed>|object
     */
    public function getData()
    {
        return $this->data;
    }
}
