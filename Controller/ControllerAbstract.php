<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

abstract class ControllerAbstract
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->twig = $twig;
        $this->contextManager = $contextManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function handleRequest(Element $element, Request $request, string $route): Response
    {
        $event = new AdminEvent($element, $request);
        $this->eventDispatcher->dispatch($event, AdminEvents::CONTEXT_PRE_CREATE);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $context = $this->contextManager->createContext($route, $element);
        if (null === $context) {
            throw new NotFoundHttpException(sprintf(
                'Cannot find context builder that supports element with id "%s"',
                $element->getId()
            ));
        }

        $response = $context->handleRequest($request);
        if ($response instanceof Response) {
            return $response;
        }

        if (false === $context->hasTemplateName()) {
            throw new ContextException(sprintf(
                'Context %s neither returned a response nor has a template name',
                get_class($context)
            ));
        }

        return new Response($this->twig->render(
            $context->getTemplateName(),
            $context->getData()
        ));
    }
}
