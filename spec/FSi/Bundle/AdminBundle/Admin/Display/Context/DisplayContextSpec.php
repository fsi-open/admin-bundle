<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\DisplayEvent;

class DisplayContextSpec extends ObjectBehavior
{
    public function let(
        Element $element,
        HandlerInterface $handler,
        DataIndexerInterface $dataIndexer,
        stdClass $displayObject,
        Display $display,
        Request $request
    ): void {
        $request->get('id', null)->willReturn('index');
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn($displayObject);
        $element->createDisplay($displayObject)->willReturn($display);
        $display->getData()->willReturn([]);

        $this->beConstructedWith([$handler], 'default_display');
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_has_array_data(Request $request): void
    {
        $this->handleRequest($request)->shouldReturn(null);
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('display');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    public function it_returns_default_template_if_element_does_not_have_one(Element $element): void
    {
        $element->hasOption('template')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_display');
    }

    public function it_returns_template_from_element_if_it_has_one(Element $element): void
    {
        $element->hasOption('template')->willReturn(true);
        $element->getOption('template')->willReturn('display.html.twig');
        $this->getTemplateName()->shouldReturn('display.html.twig');
    }

    public function it_handles_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request
    ): void {
        $handler->handleRequest(Argument::type(DisplayEvent::class), $request)->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_returns_response_from_handler(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(DisplayEvent::class), $request)->willReturn(new Response());

        $this->handleRequest($request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function getMatchers(): array
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
