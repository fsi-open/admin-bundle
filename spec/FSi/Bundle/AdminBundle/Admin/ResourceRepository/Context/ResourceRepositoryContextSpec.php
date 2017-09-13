<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ResourceRepositoryContextSpec extends ObjectBehavior
{
    function let(
        HandlerInterface $handler,
        ResourceElement $element,
        MapBuilder $builder,
        ResourceFormBuilder $resourceFormBuilder,
        FormInterface $form
    ) {
        $builder->getMap()->willReturn(['resources' => []]);
        $element->getResourceFormOptions()->willReturn([]);
        $element->getKey()->willReturn('resources');
        $resourceFormBuilder->build($element)->willReturn($form);

        $this->beConstructedWith([$handler], 'default_resource', $resourceFormBuilder);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_returns_default_template_if_element_has_none(ResourceElement $element)
    {
        $element->hasOption('template')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_resource');
        $this->hasTemplateName()->shouldReturn(true);
    }

    function it_returns_template_from_element_if_it_has_one(ResourceElement $element)
    {
        $element->hasOption('template')->willReturn(true);
        $element->getOption('template')->willReturn('resource.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('resource.html.twig');
    }

    function it_has_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        Response $response
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn($response);

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
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
