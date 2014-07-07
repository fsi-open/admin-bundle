<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create\Context as CreateContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Context as DeleteContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context as ReadContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit\Context as EditContext;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BatchControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager)
    {
        $this->beConstructedWith(
            $manager
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Controller\BatchController');
    }

    function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        GenericBatchElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"admin_element_id\""))
            ->during('batchAction', array($element, $request));
    }

    function it_throws_exception_when_context_does_not_return_response(
        ContextManager $manager,
        GenericBatchElement $element,
        ContextInterface $context,
        Request $request
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(new ContextException("Context which handles batch action must return instance of \\Symfony\\Component\\HttpFoundation\\Response"))
            ->during('batchAction', array($element, $request));
    }

    function it_return_response_from_context_in_batch_action(
        ContextManager $manager,
        GenericBatchElement $element,
        ContextInterface $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
