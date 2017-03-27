<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FormElementContextSpec extends ObjectBehavior
{
    function let(FormElement $element, FormInterface $form, HandlerInterface $handler)
    {
        $this->beConstructedWith([$handler], 'default_form');
        $element->hasOption('allow_add')->willReturn(true);
        $element->getOption('allow_add')->willReturn(true);
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_has_array_data(FormInterface $form, Request $request)
    {
        $form->createView()->willReturn('form_view');
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_returns_default_template_if_element_does_not_have_one(FormElement $element)
    {
        $element->hasOption('template_form')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_form');
        $this->hasTemplateName()->shouldReturn(true);
    }

    function it_returns_template_from_element_if_it_has_one(FormElement $element)
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('form.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('form.html.twig');
    }

    function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_returns_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_throws_exception_when_adding_is_not_allowed(
        Request $request,
        FormElement $element
    ) {
        $request->get('id')->willReturn(false);
        $element->getOption('allow_add')->willReturn(false);
        $this->shouldThrow('\Symfony\Component\HttpKernel\Exception\NotFoundHttpException')->during('handleRequest', [$request]);
    }

    public function getMatchers()
    {
        return [
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
