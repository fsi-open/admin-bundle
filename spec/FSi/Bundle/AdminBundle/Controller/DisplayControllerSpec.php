<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DisplayControllerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    function let($manager, $templating, $dispatcher)
    {
        $this->beConstructedWith($templating, $manager, $dispatcher, 'default_display');

        //always
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Element $element
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element($element, $manager, $request)
    {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('displayAction', array($element, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Element $element
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function it_render_default_template_in_display_action(
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_display', array(), null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Element $element
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_render_template_from_element_in_display_action(
        $manager,
        $element,
        $context,
        $request,
        $templating,
        $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Element $element
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_return_response_from_context_in_display_action(
        $manager,
        $element,
        $context,
        $request,
        $response
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->displayAction($element, $request)->shouldReturn($response);
    }
}
