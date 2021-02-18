<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function get_class;

class DeleteRequestHandler implements HandlerInterface
{
    /**
     * @var BatchFormValidRequestHandler
     */
    private $batchHandler;

    /**
     * @var FlashMessages
     */
    private $flashMessages;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        BatchFormValidRequestHandler $batchHandler,
        FlashMessages $flashMessages,
        RouterInterface $router
    ) {
        $this->batchHandler = $batchHandler;
        $this->flashMessages = $flashMessages;
        $this->router = $router;
    }

    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof FormEvent) {
            throw InvalidArgumentException::create(self::class, FormEvent::class, get_class($event));
        }
        try {
            $this->validateDeletion($event);
            $response = $this->batchHandler->handleRequest($event, $request);
        } catch (ForeignKeyConstraintViolationException $ex) {
            $this->flashMessages->error('crud.delete.error.foreign_key');
        }

        return $response ?? $this->getRedirectResponse($event, $request);
    }

    private function validateDeletion(FormEvent $event): void
    {
        $element = $event->getElement();
        if (true === $element->hasOption('allow_delete') && true !== $element->getOption('allow_delete')) {
            throw new LogicException(sprintf(
                'Tried to delete objects through element "%s", which has option "allow_delete" set to false',
                get_class($element)
            ));
        }
    }

    private function getRedirectResponse(FormEvent $event, Request $request): RedirectResponse
    {
        if (true === $request->query->has('redirect_uri')) {
            return new RedirectResponse($request->query->get('redirect_uri'));
        }

        $element = $event->getElement();
        if (false === $element instanceof RedirectableElement) {
            throw new LogicException(sprintf(
                'Cannot generate a redirect response for element of class "%s"',
                get_class($element)
            ));
        }

        return new RedirectResponse(
            $this->router->generate($element->getSuccessRoute(), $element->getSuccessRouteParameters())
        );
    }
}
