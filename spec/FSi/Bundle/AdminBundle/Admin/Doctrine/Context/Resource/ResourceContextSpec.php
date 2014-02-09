<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Resource;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceContextSpec extends ObjectBehavior
{
    function let(
        HandlerInterface $handler,
        ResourceElement $element,
        MapBuilder $builder,
        FormFactory $formFactory,
        FormBuilder $formBuilder,
        Form $form
    ) {
        $builder->getMap()->willReturn(array(
            'resources' => array()
        ));
        $element->getResourceFormOptions()->willReturn(array());
        $element->getKey()->willReturn('resources');
        $formFactory->createBuilder('form', array(),array())->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);

        $this->beConstructedWith(array($handler), $formFactory, $builder);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_throw_exception_when_resource_key_is_not_resource_group_key(
        MapBuilder $builder,
        ResourceElement $resourceElement,
        TextType $resource
    ) {
        $resourceElement->getKey()->willReturn('resources.resource_key');
        $builder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));

        $this->shouldThrow(
                new ContextException("resources.resource_key its not a resource group key")
            )->during(
                'setElement',
                array($resourceElement)
            );
    }

    function it_have_array_data(ResourceElement $element)
    {
        $element->getOption('title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('title');
        $this->getData()->shouldHaveKeyInArray('element');
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
