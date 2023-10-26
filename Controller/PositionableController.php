<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element;
use FSi\Bundle\AdminBundle\Event\PositionablePostMoveEvent;
use FSi\Bundle\AdminBundle\Event\PositionablePreMoveEvent;
use FSi\Bundle\AdminBundle\Model\PositionableInterface;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function is_string;
use function sprintf;

class PositionableController
{
    use DataIndexerElementFinder;

    private ManagerInterface $manager;
    private RouterInterface $router;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ManagerInterface $manager,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router
    ) {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->manager = $manager;
    }

    public function increasePositionAction(string $element, string $id, Request $request): Response
    {
        $element = $this->getElement($element);
        $entity = $this->getEntity($element, $id);

        $this->eventDispatcher->dispatch(new PositionablePreMoveEvent($request, $element, $entity));
        $entity->increasePosition();
        $this->eventDispatcher->dispatch(new PositionablePostMoveEvent($request, $element, $entity));

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    public function decreasePositionAction(string $element, string $id, Request $request): Response
    {
        $element = $this->getElement($element);
        $entity = $this->getEntity($element, $id);

        $this->eventDispatcher->dispatch(new PositionablePreMoveEvent($request, $element, $entity));
        $entity->decreasePosition();
        $this->eventDispatcher->dispatch(new PositionablePostMoveEvent($request, $element, $entity));

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param DataIndexerElement<object>&Element<object> $element
     * @param mixed $id
     */
    private function getEntity(DataIndexerElement $element, $id): PositionableInterface
    {
        $entity = $element->getDataIndexer()->getData($id);
        if (false === $entity instanceof PositionableInterface) {
            throw new RuntimeException(
                sprintf('Entity with id "%s" does not implement "%s"', $id, PositionableInterface::class)
            );
        }

        return $entity;
    }

    /**
     * @param DataIndexerElement<object>&Element<object> $element
     */
    private function getRedirectResponse(DataIndexerElement $element, Request $request): RedirectResponse
    {
        if (null !== $request->query->get('redirect_uri')) {
            $uri = $request->query->get('redirect_uri');
            if (false === is_string($uri)) {
                throw new LogicException(
                    sprintf('Query parameter redirect_uri must be a string, "%s" given.', gettype($uri))
                );
            }
        } else {
            $uri = $this->router->generate($element->getRoute(), $element->getRouteParameters());
        }

        return new RedirectResponse($uri);
    }

    /**
     * @param Element<object> $element
     */
    private function persistAndFlush(Element $element, object $entity): void
    {
        $om = $element->getObjectManager();
        $om->persist($entity);
        $om->flush();
    }
}
