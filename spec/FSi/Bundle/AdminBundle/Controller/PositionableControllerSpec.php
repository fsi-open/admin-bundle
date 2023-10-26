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
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Event\PositionablePostMoveEvent;
use FSi\Bundle\AdminBundle\Event\PositionablePreMoveEvent;
use FSi\Bundle\AdminBundle\Model\PositionableInterface;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use FSi\Component\DataIndexer\Exception\RuntimeException as DataIndexerRuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use stdClass;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PositionableControllerSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $elementManager,
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

        $elementManager->hasElement('admin_element_id')->willReturn(true);
        $elementManager->getElement('admin_element_id')->willReturn($element);

        $this->beConstructedWith($elementManager, $eventDispatcher, $router);
    }

    public function it_throws_runtime_exception_when_entity_doesnt_implement_proper_interface(
        DoctrineDataIndexer $indexer,
        Request $request,
        stdClass $entity
    ): void {
        $indexer->getData('666')->willReturn($entity);

        $this
            ->shouldThrow(RuntimeException::class)
            ->duringIncreasePositionAction('admin_element_id', '666', $request)
        ;

        $this
            ->shouldThrow(RuntimeException::class)
            ->duringDecreasePositionAction('admin_element_id', '666', $request)
        ;
    }

    public function it_throws_runtime_exception_when_specified_entity_doesnt_exist(
        DoctrineDataIndexer $indexer,
        Request $request
    ): void {
        $indexer->getData('666')->willThrow(DataIndexerRuntimeException::class);

        $this
            ->shouldThrow(DataIndexerRuntimeException::class)
            ->duringIncreasePositionAction('admin_element_id', '666', $request)
        ;

        $this
            ->shouldThrow(DataIndexerRuntimeException::class)
            ->duringDecreasePositionAction('admin_element_id', '666', $request)
        ;
    }

    public function it_decreases_position_when_decrease_position_action_called(
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        ObjectManager $om,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): void {
        $indexer->getData('1')->willReturn($positionableEntity);

        $eventDispatcher->dispatch(Argument::type(PositionablePreMoveEvent::class))->shouldBeCalled();
        $positionableEntity->decreasePosition()->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(PositionablePostMoveEvent::class))->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $response = $this->decreasePositionAction('admin_element_id', '1', $request);
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

        $eventDispatcher->dispatch(Argument::type(PositionablePreMoveEvent::class))->shouldBeCalled();
        $positionableEntity->increasePosition()->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(PositionablePostMoveEvent::class))->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $response = $this->increasePositionAction('admin_element_id', '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_redirects_to_redirect_uri_parameter_after_operation(
        EventDispatcherInterface $eventDispatcher,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        Request $request,
        ParameterBag $query
    ): void {
        $query->get('redirect_uri')->willReturn('some_redirect_uri');

        $indexer->getData('1')->willReturn($positionableEntity);
        $eventDispatcher->dispatch(Argument::type(PositionablePreMoveEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(PositionablePostMoveEvent::class))->shouldBeCalled();

        $response = $this->increasePositionAction('admin_element_id', '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');

        $response = $this->decreasePositionAction('admin_element_id', '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }
}
