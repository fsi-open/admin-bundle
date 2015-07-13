<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListControllerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    function let($manager, $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager,
            'default_list'
        );
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        $element,
        $manager,
        $request
    ) {
        $element->getId()->willReturn("my_awesome_list_element");
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('listAction', array($element, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    function it_render_default_template_in_list_action(
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_list', array(), null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_render_template_from_element_in_list_action(
        $manager,
        $element,
        $context,
        $request,
        $templating,
        $response
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_return_response_from_context_in_list_action(
        $manager,
        $element,
        $context,
        $request,
        $response
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->listAction($element, $request)->shouldReturn($response);
    }
}
