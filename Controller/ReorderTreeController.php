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
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element as AdminDoctrineElement;
use FSi\Bundle\AdminBundle\Event\MovedDownTreeEvent;
use FSi\Bundle\AdminBundle\Event\MovedUpTreeEvent;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

use function get_class;
use function gettype;
use function is_object;
use function is_string;
use function sprintf;

class ReorderTreeController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(RouterInterface $router, EventDispatcherInterface $eventDispatcher)
    {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param DataIndexerElement&AdminDoctrineElement<object> $element
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function moveUpAction(DataIndexerElement $element, string $id, Request $request): Response
    {
        $entity = $element->getDataIndexer()->getData($id);

        if (false === is_object($entity)) {
            throw new LogicException(sprintf('%s supports only objects but %s given', __CLASS__, gettype($entity)));
        }

        $this->getRepository($element)->moveUp($entity);
        $element->getObjectManager()->flush();

        $this->eventDispatcher->dispatch(new MovedUpTreeEvent($element, $request, $entity));

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param DataIndexerElement&AdminDoctrineElement<object> $element
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function moveDownAction(DataIndexerElement $element, string $id, Request $request): Response
    {
        $entity = $element->getDataIndexer()->getData($id);

        if (false === is_object($entity)) {
            throw new LogicException(sprintf('%s supports only objects but %s given', __CLASS__, gettype($entity)));
        }

        $this->getRepository($element)->moveDown($entity);
        $element->getObjectManager()->flush();

        $this->eventDispatcher->dispatch(new MovedDownTreeEvent($element, $request, $entity));

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param AdminDoctrineElement<object> $element
     * @return NestedTreeRepository
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
