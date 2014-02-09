<?php

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class DeleteFormValidRequestHandler extends AbstractFormRequestHandler
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($eventDispatcher);
        $this->router = $router;
    }

    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        if ($request->request->has('confirm')) {
            if ($event->getForm()->isValid()) {
                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $entities = $this->getEntities($event->getElement(), $request);
                foreach ($entities as $entity) {
                    $event->getElement()->delete($entity);
                }

                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }
            }

            return new RedirectResponse($this->router->generate(
                'fsi_admin_crud_list',
                array('element' => $event->getElement()->getId())
            ));
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return array
     * @throws \FSi\Bundle\AdminBundle\Exception\ContextBuilderException
     */
    protected function getEntities(ElementInterface $element, Request $request)
    {
        $data = array();
        $indexes = $request->request->get('indexes', array());

        if (!count($indexes)) {
            throw new RequestHandlerException('There must be at least one object to execute delete action');
        }

        foreach ($indexes as $index) {
            $entity = $element->getDataIndexer()->getData($index);

            if (!isset($entity)) {
                throw new RequestHandlerException(sprintf("Can't find object with id %s", $index));
            }

            $data[] = $entity;
        }

        return $data;
    }
}
