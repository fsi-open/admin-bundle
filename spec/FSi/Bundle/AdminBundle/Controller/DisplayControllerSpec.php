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
use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext;
use FSi\Bundle\AdminBundle\Admin\Display\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class DisplayControllerSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $elementManager,
        ContextManager $contextManager,
        Environment $twig,
        DisplayContext $context,
        EventDispatcherInterface $dispatcher,
        Element $element
    ): void {
        $elementManager->hasElement('admin_element_id')->willReturn(true);
        $elementManager->getElement('admin_element_id')->willReturn($element);
        $context->getTemplateName()->willReturn('default_display');

        $this->beConstructedWith($elementManager, $twig, $contextManager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Element $element,
        ContextManager $contextManager,
        DisplayContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_display', [])->willReturn('response');
        $this->displayAction('admin_element_id', $request)->getContent()->shouldBe('response');
    }

    public function it_returns_response(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Element $element,
        ContextManager $contextManager,
        DisplayContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_display', [])->willReturn('response');
        $this->displayAction('admin_element_id', $request)->getContent()->shouldBe('response');
    }

    public function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        EventDispatcherInterface $dispatcher,
        Element $element,
        ContextManager $contextManager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('admin_element_id');
        $contextManager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)->during('displayAction', ['admin_element_id', $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        EventDispatcherInterface $dispatcher,
        Element $element,
        ContextManager $contextManager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('admin_element_id');
        $contextManager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)->during('displayAction', ['admin_element_id', $request]);
    }
}
