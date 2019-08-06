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
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ResourceControllerSpec extends ObjectBehavior
{
    function let(
        ContextManager $manager,
        Environment $twig,
        ResourceRepositoryContext $context,
        EventDispatcherInterface $dispatcher
    ) {
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('default_resource');
        $this->beConstructedWith($twig, $manager, $dispatcher);
    }

    function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context,
        Environment $twig
    ) {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type(AdminEvent::class)
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_resource', [], null)->willReturn('response');

        $this->resourceAction($element, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    function it_renders_response(
        Request $request,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context,
        Environment $twig
    ) {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_resource', [], null)->willReturn('response');
        $this->resourceAction($element, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        Element $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('my_awesome_resource');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('resourceAction', [$element, $request]);
    }

    function it_throws_exception_when_no_response_and_no_template_name(
        Request $request,
        Element $element,
        ContextManager $manager,
        ResourceRepositoryContext $context
    ){
        $context->hasTemplateName()->willReturn(false);
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)
            ->during('resourceAction', [$element, $request]);
    }
}
