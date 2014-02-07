<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteContextSpec extends ObjectBehavior
{
    function let(
        HandlerInterface $handler,
        CRUDElement $element,
        FormFactory $factory,
        Form $form,
        FormView $view
    ) {
        $factory->createNamed('delete', 'form')->willReturn($form);
        $this->beConstructedWith($factory, array($handler));
        $this->setElement($element);
        $form->createView()->willReturn($view);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\DeleteContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_element_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_have_indexes_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('indexes');
    }

    function it_have_form_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('form');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_delete')->willReturn(true);
        $element->getOption('template_crud_delete')->willReturn('this_is_delete_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_delete_template.html.twig');
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
