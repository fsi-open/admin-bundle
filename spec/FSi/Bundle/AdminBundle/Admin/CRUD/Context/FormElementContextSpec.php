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
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormElementContextSpec extends ObjectBehavior
{
    function let(FormElement $element, Form $form, HandlerInterface $handler)
    {
        $this->beConstructedWith(array($handler));
        $element->createForm(null)->willReturn($form);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(Form $form, FormElement $element, DataIndexerInterface $dataIndexer, Request $request)
    {
        $form->createView()->willReturn('form_view');
        $form->getData()->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');

        $form->getData()->willReturn(array('object'));
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getIndex(array('object'))->willReturn('id');
        $this->getData()->shouldHaveKeyInArray('id');
    }

    function it_has_template(FormElement $element)
    {
        $element->hasOption('template_form')->willReturn(true);
        $element->getOption('template_form')->willReturn('this_is_form_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_form_template.html.twig');
    }

    function it_handle_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

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
