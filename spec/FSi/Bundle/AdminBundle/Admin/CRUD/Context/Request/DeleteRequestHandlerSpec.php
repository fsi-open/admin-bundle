<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\BatchFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\DeleteElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use LogicException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class DeleteRequestHandlerSpec extends ObjectBehavior
{
    public function let(
        BatchFormValidRequestHandler $batchHandler,
        FlashMessages $flashMessage,
        RouterInterface $router,
        DeleteElement $element,
        FormEvent $event,
        ParameterBag $queryParameterbag,
        Request $request,
        RedirectResponse $response
    ): void {
        $request->query = $queryParameterbag;

        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $batchHandler->handleRequest($event, $request)->willReturn($response);
        $element->hasOption('allow_delete')->willReturn(true);
        $element->getOption('allow_delete')->willReturn(true);
        $event->getElement()->willReturn($element);

        $this->beConstructedWith($batchHandler, $flashMessage, $router);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_catches_foreign_key_violation_exception(
        FormEvent $event,
        Request $request,
        BatchFormValidRequestHandler $batchHandler,
        FlashMessages $flashMessage
    ): void {
        $batchHandler->handleRequest($event, $request)->willThrow(ForeignKeyConstraintViolationException::class);
        $flashMessage->error(Argument::type('string'))->shouldBeCalled();

        $this->handleRequest($event, $request);
    }

    public function it_uses_redirect_uri_if_present(
        RouterInterface $router,
        DeleteElement $element,
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        FlashMessages $flashMessage
    ): void {
        $queryParameterbag->has('redirect_uri')->shouldNotBeCalled();
        $queryParameterbag->get('redirect_uri')->shouldNotBeCalled();
        $element->getSuccessRoute()->shouldNotBeCalled();
        $element->getSuccessRouteParameters()->shouldNotBeCalled();
        $element->getId()->shouldNotBeCalled();
        $router->generate(Argument::any())->shouldNotBeCalled();
        $flashMessage->error(Argument::any())->shouldNotBeCalled();

        $this->handleRequest($event, $request);
    }

    public function it_throws_exception_when_delete_not_allowed(
        FormEvent $event,
        Request $request,
        DeleteElement $element,
        FlashMessages $flashMessage
    ): void {
        $element->getOption('allow_delete')->willReturn(false);
        $flashMessage->error(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(LogicException::class)->during('handleRequest', [$event, $request]);
    }
}
