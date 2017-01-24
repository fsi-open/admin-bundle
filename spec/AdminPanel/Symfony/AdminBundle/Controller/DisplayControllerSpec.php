<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DisplayControllerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    public function let($manager, $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_display');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    public function it_dispatch_event_if_displatcher_present(
        $dispatcher,
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    ) {
        $this->setEventDispatcher($dispatcher);

        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_display', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element($element, $manager, $request)
    {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('displayAction', [$element, $request]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    public function it_render_default_template_in_display_action(
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_display', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function it_render_template_from_element_in_display_action(
        $manager,
        $element,
        $context,
        $request,
        $templating,
        $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function it_return_response_from_context_in_display_action(
        $manager,
        $element,
        $context,
        $request,
        $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->displayAction($element, $request)->shouldReturn($response);
    }
}
