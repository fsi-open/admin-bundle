<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext;
use FSi\Bundle\AdminBundle\Admin\Display\Element;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class DisplayControllerSpec extends ObjectBehavior
{
    public function let(
        ContextManager $manager,
        Environment $twig,
        DisplayContext $context,
        EventDispatcherInterface $dispatcher
    ): void {
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('default_display');

        $this->beConstructedWith($twig, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Element $element,
        ContextManager $manager,
        DisplayContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(
            Argument::type(AdminEvent::class),
            AdminEvents::CONTEXT_PRE_CREATE
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_display', [])->willReturn('response');
        $this->displayAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_returns_response(
        Request $request,
        Element $element,
        ContextManager $manager,
        DisplayContext $context,
        Environment $twig
    ): void {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_display', [])->willReturn('response');
        $this->displayAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        Element $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('displayAction', [$element, $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        Element $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('displayAction', [$element, $request]);
    }
}
