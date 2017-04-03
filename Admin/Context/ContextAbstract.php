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
     * @var string
     */
    private $template;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param string|null
     */
    public function __construct(array $requestHandlers, $template = null)
    {
        $this->requestHandlers = $requestHandlers;
        $this->template = $template;
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

    public function hasTemplateName()
    {
        return !empty($this->template);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->template;
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
