<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BatchControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, DelegatingEngine $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager
        );
    }

    function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        BatchElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('batchAction', array($element, $request));
    }

    function it_throws_exception_when_context_does_not_return_response(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow('FSi\Bundle\AdminBundle\Exception\ContextException')
            ->during('batchAction', array($element, $request));
    }

    function it_return_response_from_context_in_batch_action(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
