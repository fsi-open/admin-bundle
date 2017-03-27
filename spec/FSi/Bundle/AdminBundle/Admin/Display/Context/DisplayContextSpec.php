<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Display\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Display\Element;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DisplayContextSpec extends ObjectBehavior
{
    function let(
        Element $element,
        HandlerInterface $handler,
        DataIndexerInterface $dataIndexer,
        stdClass $displayObject,
        Display $display,
        Request $request
    ) {
        $request->get('id', null)->willReturn('index');
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn($displayObject);
        $element->createDisplay($displayObject)->willReturn($display);

        $this->beConstructedWith([$handler], 'default_display');
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_has_array_data(Request $request)
    {
        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('display');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_returns_default_template_if_element_does_not_have_one(Element $element)
    {
        $element->hasOption('template')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_display');
        $this->hasTemplateName()->shouldReturn(true);
    }

    function it_returns_template_from_element_if_it_has_one(Element $element)
    {
        $element->hasOption('template')->willReturn(true);
        $element->getOption('template')->willReturn('display.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('display.html.twig');
    }

    function it_handles_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\DisplayEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_returns_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\DisplayEvent'), $request)
            ->willReturn(new Response());

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
