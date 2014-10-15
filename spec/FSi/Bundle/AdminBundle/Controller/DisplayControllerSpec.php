<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\Display;
use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DisplayControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, DelegatingEngine $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_display');
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        Display\Element $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"my_awesome_display\""))
            ->during('displayAction', array($element, $request));
    }

    function it_render_default_template_in_display_action(
        Request $request,
        Response $response,
        Display\Element $element,
        ContextManager $manager,
        DisplayContext $context,
        DelegatingEngine $templating
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_display', array(), null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_display_action(
        ContextManager $manager,
        Display\Element $element,
        DisplayContext $context,
        Request $request,
        DelegatingEngine $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_display_action(
        ContextManager $manager,
        Display\Element $element,
        DisplayContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->displayAction($element, $request)->shouldReturn($response);
    }
}
