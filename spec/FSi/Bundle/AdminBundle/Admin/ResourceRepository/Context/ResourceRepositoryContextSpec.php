<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class ResourceRepositoryContextSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $builder
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder $resourceFormBuilder
     * @param \Symfony\Component\Form\Form $form
     */
    function let($handler, $element, $builder, $resourceFormBuilder, $form)
    {
        $builder->getMap()->willReturn(array(
            'resources' => array()
        ));
        $element->getResourceFormOptions()->willReturn(array());
        $element->getKey()->willReturn('resources');
        $resourceFormBuilder->build($element)->willReturn($form);

        $this->beConstructedWith(array($handler), $resourceFormBuilder);
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
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_handle_request_with_request_handlers($handler, $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_return_response_from_handler($handler, $request)
    {
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
