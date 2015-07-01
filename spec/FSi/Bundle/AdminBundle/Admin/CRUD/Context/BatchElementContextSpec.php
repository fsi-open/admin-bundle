<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class BatchElementContextSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement $element
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\Form $batchForm
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     */
    function let($element, $formBuilder, $batchForm, $handler)
    {
        $this->beConstructedWith(array($handler), $formBuilder);
        $formBuilder->getForm()->willReturn($batchForm);
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

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterBag
     */
    function it_handle_request_with_request_handlers($handler, $request, $requestParameterBag)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $request->request = $requestParameterBag;
        $requestParameterBag->get('indexes', array())->willReturn(array());

        $this->handleRequest($request)->shouldReturn(null);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterBag
     */
    function it_return_response_from_handler($handler, $request, $requestParameterBag)
    {
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
