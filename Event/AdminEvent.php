<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminEvent extends Event
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\ElementInterface
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
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @param Request $request
     */
    public function __construct(ElementInterface $element, Request $request)
    {
        $this->element = $element;
        $this->request = $request;
        $this->response = null;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return Request
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
     * @return AdminEvent
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
