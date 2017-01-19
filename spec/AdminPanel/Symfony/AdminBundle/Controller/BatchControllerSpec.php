<?php


namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BatchControllerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function let($manager, $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager
        );
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\BatchElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_dispatch_event_if_displatcher_present(
        $dispatcher,
        $manager,
        $element,
        $context,
        $request,
        $response
    )
    {
        $this->setEventDispatcher($dispatcher);

        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        $element,
        $manager,
        $request
    )
    {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('batchAction', array($element, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\BatchElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throws_exception_when_context_does_not_return_response($manager, $element, $context, $request)
    {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->hasTemplateName()->willReturn(false);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow('AdminPanel\Symfony\AdminBundle\Exception\ContextException')
            ->during('batchAction', array($element, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\BatchElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_return_response_from_context_in_batch_action($manager, $element, $context, $request, $response)
    {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
