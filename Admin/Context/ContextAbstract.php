<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;

abstract class ContextAbstract implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     */
    public function __construct(array $requestHandlers)
    {
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, Element $element)
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        return $this->supportsElement($element);
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = $this->createEvent($request);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return null;
    }

    /**
     * @return string
     */
    abstract protected function getSupportedRoute();

    /**
     * @param Element $element
     * @return bool
     */
    abstract protected function supportsElement(Element $element);

    /**
     * @param Request $request
     * @return AdminEvent
     */
    abstract protected function createEvent(Request $request);
}
