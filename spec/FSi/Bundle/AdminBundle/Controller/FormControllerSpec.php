<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;

class FormControllerSpec extends ObjectBehavior
{
    public function let(
        EngineInterface $templating,
        ContextManager $manager,
        FormElementContext $context,
        EventDispatcherInterface $dispatcher
    ): void {
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('default_form');

        $this->beConstructedWith($templating, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context,
        EngineInterface $templating
    ): void {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type(AdminEvent::class)
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_form', [], null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    public function it_returns_response(
        Request $request,
        Response $response,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context,
        EngineInterface $templating
    ): void {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_form', [], null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        GenericFormElement $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);
        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('formAction', [$element, $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        Request $request,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context
    ): void {
        $context->hasTemplateName()->willReturn(false);
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)
            ->during('formAction', [$element, $request]);
    }
}
