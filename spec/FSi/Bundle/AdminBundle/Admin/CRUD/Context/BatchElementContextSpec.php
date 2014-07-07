<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchElementContextSpec extends ObjectBehavior
{
    function let(BatchElement $element, FormFactory $formFactory, Form $batchForm, HandlerInterface $handler)
    {
        $this->beConstructedWith(array($handler), $formFactory);
        $formFactory->createNamed('batch_action', 'form')->willReturn($batchForm);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
        $this->getData()->shouldHaveKeyInArray('indexes');
    }

    function it_does_not_have_template_name()
    {
        $this->hasTemplateName()->shouldReturn(false);
        $this->getTemplateName()->shouldReturn(null);
    }

    function it_handle_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request,
        ParameterBag $requestParameterBag
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $request->request = $requestParameterBag;
        $requestParameterBag->get('indexes', array())->willReturn(array());

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        ParameterBag $requestParameterBag
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $request->request = $requestParameterBag;
        $requestParameterBag->get('indexes', array())->willReturn(array());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return array(
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        );
    }
}
