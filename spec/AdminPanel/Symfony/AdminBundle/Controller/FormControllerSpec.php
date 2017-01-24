<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormControllerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function let($manager, $templating, $dispatcher)
    {
        $this->beConstructedWith(
            $templating,
            $manager,
            'default_form'
        );
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\FormElementContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
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

        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_form', [], null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        $element,
        $manager,
        $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('formAction', [$element, $request]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\FormElementContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function it_render_default_template_in_form_action(
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_form', [], null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\FormElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function it_render_template_from_element_in_form_action(
        $manager,
        $element,
        $context,
        $request,
        $templating,
        $response
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\FormElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function it_return_response_from_context_in_form_action(
        $manager,
        $element,
        $context,
        $request,
        $response
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->formAction($element, $request)->shouldReturn($response);
    }
}
