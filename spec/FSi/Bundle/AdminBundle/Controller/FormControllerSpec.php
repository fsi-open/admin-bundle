<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, EngineInterface $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager,
            'default_form'
        );
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        GenericFormElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"admin_element_id\""))
            ->during('formAction', array($element, $request));
    }

    function it_render_default_template_in_form_action(
        Request $request,
        Response $response,
        GenericFormElement $element,
        ContextManager $manager,
        FormElementContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_form', array(), null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_form_action(
        ContextManager $manager,
        GenericFormElement $element,
        FormElementContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->formAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_form_action(
        ContextManager $manager,
        GenericFormElement $element,
        FormElementContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_form', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->formAction($element, $request)->shouldReturn($response);
    }
}
