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

class BatchControllerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function let($manager, $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager
        );
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        $element,
        $manager,
        $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('batchAction', array($element, $request));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throws_exception_when_context_does_not_return_response($manager, $element, $context, $request)
    {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->hasTemplateName()->willReturn(false);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow('FSi\Bundle\AdminBundle\Exception\ContextException')
            ->during('batchAction', array($element, $request));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_return_response_from_context_in_batch_action($manager, $element, $context, $request, $response)
    {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
