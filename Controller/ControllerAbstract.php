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
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

use function get_class;
use function sprintf;

abstract class ControllerAbstract
{
    protected Environment $twig;
    protected ContextManager $contextManager;
    private ManagerInterface $manager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ManagerInterface $manager,
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->contextManager = $contextManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws NotFoundHttpException
     * @throws ContextException
     */
    protected function handleRequest(Element $element, Request $request, string $route): Response
    {
        $event = new AdminContextPreCreateEvent($element, $request);
        $this->eventDispatcher->dispatch($event);
        $response = $event->getResponse();
        if (null !== $response) {
            return $response;
        }

        $context = $this->contextManager->createContext($route, $element);
        if (null === $context) {
            throw new NotFoundHttpException(sprintf(
                'Cannot find context builder that supports element with id "%s"',
                $element->getId()
            ));
        }

        $response = $context->handleRequest($request);
        if (null !== $response) {
            return $response;
        }

        $templateName = $context->getTemplateName();
        if (null === $templateName) {
            throw new ContextException(sprintf(
                'Context %s neither returned a response nor has a template name',
                get_class($context)
            ));
        }

        return new Response($this->twig->render($templateName, $context->getData()));
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getElement(string $elementId): Element
    {
        if (false === $this->manager->hasElement($elementId)) {
            throw new NotFoundHttpException("Admin element with id {$elementId} does not exist.");
        }

        return $this->manager->getElement($elementId);
    }
}
