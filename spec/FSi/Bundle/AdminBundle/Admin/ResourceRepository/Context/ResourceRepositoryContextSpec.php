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
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;

class ResourceRepositoryContextSpec extends ObjectBehavior
{
    public function let(
        HandlerInterface $handler,
        ResourceElement $element,
        MapBuilder $builder,
        ResourceFormBuilder $resourceFormBuilder,
        FormInterface $form
    ): void {
        $builder->getMap()->willReturn(['resources' => []]);
        $element->getResourceFormOptions()->willReturn([]);
        $element->getKey()->willReturn('resources');
        $resourceFormBuilder->build($element)->willReturn($form);

        $this->beConstructedWith([$handler], 'default_resource', $resourceFormBuilder);
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_returns_default_template_if_element_has_none(ResourceElement $element): void
    {
        $element->hasOption('template')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_resource');
        $this->hasTemplateName()->shouldReturn(true);
    }

    public function it_returns_template_from_element_if_it_has_one(ResourceElement $element): void
    {
        $element->hasOption('template')->willReturn(true);
        $element->getOption('template')->willReturn('resource.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('resource.html.twig');
    }

    public function it_has_array_data(): void
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    public function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        Response $response
    ): void {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn($response);

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf(Response::class);
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
