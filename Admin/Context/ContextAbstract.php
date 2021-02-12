<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ContextAbstract implements ContextInterface
{
    /**
     * @var array<HandlerInterface>
     */
    private $requestHandlers;

    /**
     * @var string|null
     */
    private $template;

    public static function getPriority(): int
    {
        return 0;
    }

    /**
     * @param iterable<HandlerInterface> $requestHandlers
     * @param string|null $template
     */
    public function __construct(iterable $requestHandlers, ?string $template = null)
    {
        $this->requestHandlers = $requestHandlers;
        $this->template = $template;
    }

    public function supports(string $route, Element $element): bool
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        return $this->supportsElement($element);
    }

    public function handleRequest(Request $request): ?Response
    {
        $event = $this->createEvent($request);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (null !== $response) {
                return $response;
            }
        }

        return null;
    }

    public function hasTemplateName(): bool
    {
        return null !== $this->template;
    }

    public function getTemplateName(): ?string
    {
        return $this->template;
    }

    abstract protected function getSupportedRoute(): string;

    abstract protected function supportsElement(Element $element): bool;

    abstract protected function createEvent(Request $request): AdminEvent;
}
