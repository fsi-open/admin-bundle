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
use FSi\Bundle\AdminBundle\Admin\Element as AdminElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element as AdminDoctrineElement;
use FSi\Bundle\AdminBundle\Event\MovedDownTreeEvent;
use FSi\Bundle\AdminBundle\Event\MovedUpTreeEvent;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use InvalidArgumentException;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function get_class;
use function gettype;
use function is_object;
use function is_string;
use function sprintf;

class ReorderTreeController
{
    use DataIndexerElementFinder;

    private ManagerInterface $manager;
    private RouterInterface $router;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ManagerInterface $manager,
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->manager = $manager;
    }

    public function moveUpAction(string $element, string $id, Request $request): Response
    {
        $element = $this->getElement($element);
        $entity = $this->getEntity($element, $id);

        $this->getRepository($element)->moveUp($entity);
        $element->getObjectManager()->flush();

        $this->eventDispatcher->dispatch(new MovedUpTreeEvent($element, $request, $entity));

        return $this->getRedirectResponse($element, $request);
    }

    public function moveDownAction(string $element, string $id, Request $request): Response
    {
        $element = $this->getElement($element);
        $entity = $this->getEntity($element, $id);

        $this->getRepository($element)->moveDown($entity);
        $element->getObjectManager()->flush();

        $this->eventDispatcher->dispatch(new MovedDownTreeEvent($element, $request, $entity));

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param DataIndexerElement<object>&AdminDoctrineElement<object> $element
     */
    private function getEntity(DataIndexerElement $element, string $id): object
    {
        $entity = $element->getDataIndexer()->getData($id);
        if (false === is_object($entity)) {
            throw new LogicException(
                sprintf('%s supports only objects but %s given', __CLASS__, gettype($entity))
            );
        }

        return $entity;
    }

    /**
     * @template T of object
     * @param AdminDoctrineElement<T> $element
     * @return NestedTreeRepository<T>
     */
    private function getRepository(AdminDoctrineElement $element): NestedTreeRepository
    {
        $repository = $element->getRepository();
        if (false === $repository instanceof NestedTreeRepository) {
            throw new InvalidArgumentException(sprintf(
                'Repository "%s" needs to extend "%s',
                get_class($repository),
                NestedTreeRepository::class
            ));
        }

        return $repository;
    }

    private function getRedirectResponse(AdminElement $element, Request $request): RedirectResponse
    {
        $redirectUri = $request->query->get('redirect_uri');
        if (null !== $redirectUri && '' !== $redirectUri) {
            if (false === is_string($redirectUri)) {
                throw new LogicException(
                    sprintf('Query parameter redirect_uri must be a string, "%s" given.', gettype($redirectUri))
                );
            }

            $uri = $redirectUri;
        } else {
            $uri = $this->router->generate($element->getRoute(), $element->getRouteParameters());
        }

        return new RedirectResponse($uri);
    }
}
