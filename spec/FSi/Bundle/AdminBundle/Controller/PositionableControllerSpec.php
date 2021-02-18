<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Controller;

use Doctrine\Persistence\ObjectManager;
use FSi\Bundle\AdminBundle\Controller\PositionableController;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Event\PositionableEvent;
use FSi\Bundle\AdminBundle\Event\PositionableEvents;
use FSi\Bundle\AdminBundle\Model\PositionableInterface;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use FSi\Component\DataIndexer\Exception\RuntimeException as DataIndexerRuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RuntimeException;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @mixin PositionableController
 */
class PositionableControllerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        ObjectManager $om,
        Request $request,
        ParameterBag $query
    ): void {
        $request->query = $query;
        $element->getId()->willReturn('slides');
        $element->getDataIndexer()->willReturn($indexer);
        $element->getObjectManager()->willReturn($om);
        $element->getRoute()->willReturn('fsi_admin_list');
        $element->getRouteParameters()->willReturn(['element' => 'slides']);
        $router->generate('fsi_admin_list', ['element' => 'slides'])->willReturn('sample-path');

        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_throws_runtime_exception_when_entity_doesnt_implement_proper_interface(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        Request $request,
        stdClass $entity
    ): void {
        $indexer->getData('666')->willReturn($entity);

        $this->shouldThrow(RuntimeException::class)->duringIncreasePositionAction($element, '666', $request);
        $this->shouldThrow(RuntimeException::class)->duringDecreasePositionAction($element, '666', $request);
    }

    public function it_throws_runtime_exception_when_specified_entity_doesnt_exist(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        Request $request
    ): void {
        $indexer->getData('666')->willThrow(DataIndexerRuntimeException::class);

        $this->shouldThrow(DataIndexerRuntimeException::class)->duringIncreasePositionAction($element, '666', $request);
        $this->shouldThrow(DataIndexerRuntimeException::class)->duringDecreasePositionAction($element, '666', $request);
    }

    public function it_decreases_position_when_decrease_position_action_called(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        ObjectManager $om,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): void {
        $indexer->getData('1')->willReturn($positionableEntity);

        $eventDispatcher->dispatch(
            Argument::type(PositionableEvent::class),
            PositionableEvents::PRE_APPLY
        )->shouldBeCalled();
        $positionableEntity->decreasePosition()->shouldBeCalled();
        $eventDispatcher->dispatch(
            Argument::type(PositionableEvent::class),
            PositionableEvents::POST_APPLY
        )->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $response = $this->decreasePositionAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_increases_position_when_increase_position_action_called(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        ObjectManager $om,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): void {
        $indexer->getData('1')->willReturn($positionableEntity);

        $eventDispatcher->dispatch(
            Argument::type(PositionableEvent::class),
            PositionableEvents::PRE_APPLY
        )->shouldBeCalled();
        $positionableEntity->increasePosition()->shouldBeCalled();
        $eventDispatcher->dispatch(
            Argument::type(PositionableEvent::class),
            PositionableEvents::POST_APPLY
        )->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $response = $this->increasePositionAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_redirects_to_redirect_uri_parameter_after_operation(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        Request $request,
        ParameterBag $query
    ): void {
        $query->get('redirect_uri')->willReturn('some_redirect_uri');

        $indexer->getData('1')->willReturn($positionableEntity);

        $response = $this->increasePositionAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');

        $response = $this->decreasePositionAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }
}
