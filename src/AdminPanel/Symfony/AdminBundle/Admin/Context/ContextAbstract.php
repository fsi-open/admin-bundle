<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
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
