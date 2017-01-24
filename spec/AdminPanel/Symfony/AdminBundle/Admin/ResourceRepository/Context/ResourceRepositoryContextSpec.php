<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class ResourceRepositoryContextSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $builder
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder $resourceFormBuilder
     * @param \Symfony\Component\Form\Form $form
     */
    public function let($handler, $element, $builder, $resourceFormBuilder, $form)
    {
        $builder->getMap()->willReturn([
            'resources' => []
        ]);
        $element->getResourceFormOptions()->willReturn([]);
        $element->getKey()->willReturn('resources');
        $resourceFormBuilder->build($element)->willReturn($form);

        $this->beConstructedWith([$handler], $resourceFormBuilder);
        $this->setElement($element);
    }

    public function it_is_context()
    {
        $this->shouldBeAnInstanceOf('AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface');
    }

    public function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_handle_request_with_request_handlers($handler, $request)
    {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_return_response_from_handler($handler, $request)
    {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return [
            'haveKeyInArray' => function ($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
