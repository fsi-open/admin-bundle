<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminPositionableBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataIndexerElement;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\Element;
use \RuntimeException;
use FSi\Bundle\AdminPositionableBundle\Model\PositionableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PositionableController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param DataIndexerElement $element
     * @param $id
     * @param Request $request
     * @throws RuntimeException
     * @return RedirectResponse
     */
    public function increasePositionAction(
        DataIndexerElement $element,
        $id,
        Request $request
    ) {
        $entity = $this->getEntity($element, $id);
        $entity->increasePosition();

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param DataIndexerElement $element
     * @param $id
     * @param Request $request
     * @throws RuntimeException
     * @return RedirectResponse
     */
    public function decreasePositionAction(
        DataIndexerElement $element,
        $id,
        Request $request
    ) {
        $entity = $this->getEntity($element, $id);
        $entity->decreasePosition();

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param DataIndexerElement $element
     * @param int $id
     * @throws RuntimeException
     * @return PositionableInterface
     */
    private function getEntity(DataIndexerElement $element, $id)
    {
        $entity = $element->getDataIndexer()->getData($id);

        if (!$entity instanceof PositionableInterface) {
            throw new RuntimeException(
                sprintf('Entity with id %s does not implement PositionableInterface', $id)
            );
        }

        return $entity;
    }

    /**
     * @param DataIndexerElement $element
     * @param Request $request
     * @return RedirectResponse
     */
    private function getRedirectResponse(DataIndexerElement $element, Request $request)
    {
        if ($request->query->get('redirect_uri')) {
            $uri = $request->query->get('redirect_uri');
        } else {
            $uri = $this->router->generate(
                $element->getRoute(),
                $element->getRouteParameters()
            );
        }

        return new RedirectResponse($uri);
    }

    /**
     * @param Element $element
     * @param $entity
     */
    private function persistAndFlush(Element $element, $entity)
    {
        $om = $element->getObjectManager();
        $om->persist($entity);
        $om->flush();
    }
}
