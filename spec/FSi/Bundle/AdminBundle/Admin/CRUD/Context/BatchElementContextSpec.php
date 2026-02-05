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
use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function class_exists;

class BatchElementContextSpec extends ObjectBehavior
{
    public function let(
        BatchElement $element,
        FormBuilderInterface $formBuilder,
        FormInterface $batchForm,
        HandlerInterface $handler
    ): void {
        $this->beConstructedWith([$handler], $formBuilder);
        $formBuilder->getForm()->willReturn($batchForm);
        $batchForm->createView()->willReturn(new FormView());
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_has_array_data(): void
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
        $this->getData()->shouldHaveKeyInArray('indexes');
    }

    public function it_does_not_have_template_name(): void
    {
        $this->getTemplateName()->shouldReturn(null);
    }

    public function it_handles_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request
    ): void {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn(null);

        if (class_exists(InputBag::class)) {
            $requestParameterBag = new InputBag();
        } else {
            $requestParameterBag = new ParameterBag();
        }
        $requestParameterBag->set('indexes', []);
        $request->request = $requestParameterBag;

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        Response $response
    ): void {
        $handler->handleRequest(Argument::type(FormEvent::class), $request)
            ->willReturn($response);

        if (class_exists(InputBag::class)) {
            $requestParameterBag = new InputBag();
        } else {
            $requestParameterBag = new ParameterBag();
        }
        $requestParameterBag->set('indexes', []);
        $request->request = $requestParameterBag;

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
