<?php


namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminEvent extends Event
{
    /**
     * @var \AdminPanel\Symfony\AdminBundle\Admin\Element
     */
    protected $element;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Element $element, Request $request)
    {
        $this->element = $element;
        $this->request = $request;
    }

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return isset($this->response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \AdminPanel\Symfony\AdminBundle\Event\AdminEvent
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
