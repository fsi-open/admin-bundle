<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormHavingTemplateDataElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    function it_has_array_data(
        FormElement $element,
        FormInterface $form,
        FormView $formView,
        Request $request
    ) {
        $form->createView()->willReturn($formView);
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);

        $this->getData()->shouldReturn([
            'form' => $formView,
            'element' => $element,
            'additionalData' => []
        ]);
    }

    public function it_uses_data_from_element_with_template_data(
        Request $request,
        FormInterface $form,
        FormView $formView,
        FormHavingTemplateDataElement $elementWithTemplateData
    ) {
        $elementWithTemplateData->hasOption('allow_add')->willReturn(true);
        $elementWithTemplateData->getOption('allow_add')->willReturn(true);
        $this->setElement($elementWithTemplateData);

        $form->createView()->willReturn($formView);
        $elementWithTemplateData->createForm(null)->willReturn($form);
        $elementWithTemplateData->getTemplateData()->willReturn(['parameter' => 'value']);

        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldReturn([
            'form' => $formView,
            'element' => $elementWithTemplateData,
            'additionalData' => ['parameter' => 'value']
        ]);
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
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_returns_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_throws_exception_when_adding_is_not_allowed(
        Request $request,
        FormElement $element
    ) {
        $request->get('id')->willReturn(false);
        $element->getOption('allow_add')->willReturn(false);
        $this->shouldThrow(NotFoundHttpException::class)->during('handleRequest', [$request]);
    }
}
