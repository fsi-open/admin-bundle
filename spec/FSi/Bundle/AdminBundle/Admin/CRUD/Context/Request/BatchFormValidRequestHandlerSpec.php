<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    function let($eventDispatcher, $event, $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\ListEvent $listEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_form_event($listEvent, $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', array($listEvent, $request));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $formEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_redirectable_element($formEvent, $request)
    {
        $formEvent->getElement()->willReturn(new \stdClass());

        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', array($formEvent, $request));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    function it_handle_POST_request(
        $event,
        $request,
        $requestParameterbag,
        $queryParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer,
        $router
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_list_id'));
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', array('element' => 'element_list_id'))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     */
    function it_return_redirect_response_with_redirect_uri_passed_by_request(
        $event,
        $request,
        $requestParameterbag,
        $queryParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_list_id'));
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $queryParameterbag->has('redirect_uri')->willReturn(true);
        $queryParameterbag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     */
    function it_return_response_from_pre_apply_event($event, $element, $request, $eventDispatcher, $form)
    {
        $request->isMethod('POST')->willReturn(true);
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     */
    function it_return_response_from_post_apply_event(
        $event,
        $request,
        $requestParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
