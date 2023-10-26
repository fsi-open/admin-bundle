<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class FormControllerSpec extends ObjectBehavior
{
    public function let(
        Environment $twig,
        ManagerInterface $elementManager,
        ContextManager $contextManager,
        FormElementContext $context,
        EventDispatcherInterface $dispatcher,
        GenericFormElement $element
    ): void {
        $elementManager->hasElement('admin_element_id')->willReturn(true);
        $elementManager->getElement('admin_element_id')->willReturn($element);
        $context->getTemplateName()->willReturn('default_form');

        $this->beConstructedWith($elementManager, $twig, $contextManager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        GenericFormElement $element,
        ContextManager $contextManager,
        FormElementContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_form', [])->willReturn('response');
        $this->formAction('admin_element_id', $request)->getContent()->shouldBe('response');
    }

    public function it_returns_response(
        EventDispatcherInterface $dispatcher,
        Request $request,
        GenericFormElement $element,
        ContextManager $contextManager,
        FormElementContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_form', [])->willReturn('response');
        $this->formAction('admin_element_id', $request)->getContent()->shouldBe('response');
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        EventDispatcherInterface $dispatcher,
        GenericFormElement $element,
        ContextManager $contextManager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('admin_element_id');
        $contextManager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);
        $this->shouldThrow(NotFoundHttpException::class)->during('formAction', ['admin_element_id', $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        EventDispatcherInterface $dispatcher,
        Request $request,
        GenericFormElement $element,
        ContextManager $contextManager,
        FormElementContext $context
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $context->getTemplateName()->willReturn(null);
        $contextManager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)->during('formAction', ['admin_element_id', $request]);
    }
}
