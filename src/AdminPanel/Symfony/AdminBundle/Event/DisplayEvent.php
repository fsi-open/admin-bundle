<?php


namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Display\Display;
use Symfony\Component\HttpFoundation\Request;

class DisplayEvent extends AdminEvent
{
    /**
     * @var \AdminPanel\Symfony\AdminBundle\Display\Display
     */
    private $display;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AdminPanel\Symfony\AdminBundle\Display\Display $display
     */
    public function __construct(Element $element, Request $request, Display $display)
    {
        parent::__construct($element, $request);
        $this->display = $display;
    }

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Display\Display
     */
    public function getDisplay()
    {
        return $this->display;
    }
}
