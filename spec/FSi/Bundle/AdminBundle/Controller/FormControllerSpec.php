<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class FormControllerSpec extends ObjectBehavior
{
    public function let(
        Environment $twig,
        ContextManager $manager,
        FormElementContext $context,
        EventDispatcherInterface $dispatcher
    ): void {
        $context->getTemplateName()->willReturn('default_form');

        $this->beConstructedWith($twig, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_form', [])->willReturn('response');
        $this->formAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_returns_response(
        EventDispatcherInterface $dispatcher,
        AdminEvent $event,
        Request $request,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_form', [])->willReturn('response');
        $this->formAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        EventDispatcherInterface $dispatcher,
        GenericFormElement $element,
        ContextManager $manager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);
        $this->shouldThrow(NotFoundHttpException::class)->during('formAction', [$element, $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        EventDispatcherInterface $dispatcher,
        Request $request,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $context->getTemplateName()->willReturn(null);
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)->during('formAction', [$element, $request]);
    }
}
