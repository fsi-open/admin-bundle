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
    /**
     * @var Display
     */
    private $display;

    public function __construct(Element $element, Request $request, Display $display)
    {
        parent::__construct($element, $request);

        $this->display = $display;
    }

    public function getDisplay(): Display
    {
        return $this->display;
    }
}
