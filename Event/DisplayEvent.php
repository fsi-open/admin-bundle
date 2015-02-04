<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Display\Display;
use Symfony\Component\HttpFoundation\Request;

class DisplayEvent extends AdminEvent
{
    /**
     * @var \FSi\Bundle\AdminBundle\Display\Display
     */
    private $display;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FSi\Bundle\AdminBundle\Display\Display $display
     */
    public function __construct(Element $element, Request $request, Display $display)
    {
        parent::__construct($element, $request);
        $this->display = $display;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Display\Display
     */
    public function getDisplay()
    {
        return $this->display;
    }
}
