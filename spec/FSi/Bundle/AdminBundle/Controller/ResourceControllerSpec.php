<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Twig\Environment;

class ResourceControllerSpec extends ObjectBehavior
{
    public function let(
        ContextManager $manager,
        Environment $twig,
        ResourceRepositoryContext $context,
        EventDispatcherInterface $dispatcher
    ): void {
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('default_resource');
        $this->beConstructedWith($twig, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context,
        Environment $twig
    ) {
        $dispatcher->dispatch(
            Argument::type(AdminEvent::class),
            AdminEvents::CONTEXT_PRE_CREATE
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_resource', [])->willReturn('response');

        $this->resourceAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_renders_response(
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context,
        Environment $twig
    ): void {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_resource', [])->willReturn('response');
        $this->resourceAction($element, $request)->getContent()->shouldBe('response');
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        Element $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('my_awesome_resource');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('resourceAction', [$element, $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        Request $request,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context
    ): void {
        $context->hasTemplateName()->willReturn(false);
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)
            ->during('resourceAction', [$element, $request]);
    }
}
