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
    public function let(FormElement $element, FormInterface $form, HandlerInterface $handler): void
    {
        $this->beConstructedWith([$handler], 'default_form');
        $element->hasOption('allow_add')->willReturn(true);
        $element->getOption('allow_add')->willReturn(true);
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_has_array_data(
        FormElement $element,
        FormInterface $form,
        FormView $formView,
        Request $request
    ): void {
        $form->createView()->willReturn($formView);
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);

        $this->getData()->shouldReturn(['form' => $formView, 'element' => $element]);
    }

    public function it_returns_default_template_if_element_does_not_have_one(FormElement $element): void
    {
        $element->hasOption('template_form')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_form');
        $this->hasTemplateName()->shouldReturn(true);
    }

    public function it_returns_template_from_element_if_it_has_one(FormElement $element): void
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('form.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('form.html.twig');
    }

    public function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_returns_response_from_handler(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_throws_exception_when_adding_is_not_allowed(Request $request, FormElement $element): void
    {
        $request->get('id')->willReturn(null);
        $element->getOption('allow_add')->willReturn(false);
        $this->shouldThrow(NotFoundHttpException::class)->during('handleRequest', [$request]);
    }
}
