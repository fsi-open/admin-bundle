<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Display\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Display\GenericDisplayElement;
use FSi\Bundle\AdminBundle\Event\DisplayEvent;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class DisplayContext implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

    /**
     * @var GenericDisplayElement
     */
    private $element;

    /**
     * @var \FSi\Bundle\AdminBundle\Display\Display
     */
    private $display;

    /**
     * @param $requestHandlers
     */
    function __construct($requestHandlers)
    {
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * @param GenericDisplayElement $element
     */
    public function setElement(GenericDisplayElement $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        if (!$this->hasObject($request)) {
            throw new RuntimeException(sprintf("Cant find element \"%s\" in request", $this->element->getClassName()));
        }

        $this->display = $this->element->createDisplayElement($this->getObject($request));
        $event = new DisplayEvent($this->element, $request, $this->display);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }

    /**
     * @return boolean
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template');
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'display' => $this->display->createView(),
            'element' => $this->element,
        );
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function hasObject(Request $request)
    {
        $data = $this->element->getDataIndexer()->getData($this->getObjectId($request));

        return isset($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getObject(Request $request)
    {
        $data = $this->element->getDataIndexer()->getData($this->getObjectId($request));

        return $data;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getObjectId(Request $request)
    {
        return $request->get('id', null);
    }
}
