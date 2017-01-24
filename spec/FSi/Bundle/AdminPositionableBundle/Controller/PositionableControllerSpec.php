<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminPositionableBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\CRUDElement;
use \stdClass;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\Bundle\AdminPositionableBundle\Model\PositionableInterface;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @mixin \FSi\Bundle\AdminPositionableBundle\Controller\PositionableController
 */
class PositionableControllerSpec extends ObjectBehavior
{
    public function let(
        RouterInterface $router,
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        ObjectManager $om,
        Request $request,
        ParameterBag $query
    ) {
        $request->query = $query;
        $element->getId()->willReturn('slides');
        $element->getDataIndexer()->willReturn($indexer);
        $element->getObjectManager()->willReturn($om);
        $element->getRoute()->willReturn('fsi_admin_crud_list');
        $element->getRouteParameters()->willReturn(['element' => 'slides']);

        $this->beConstructedWith($router);
    }

    public function it_throws_runtime_exception_when_entity_doesnt_implement_proper_interface(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        Request $request
    ) {
        $indexer->getData(666)->willReturn(new stdClass());

        $this->shouldThrow('\RuntimeException')
            ->duringIncreasePositionAction($element, 666, $request);

        $this->shouldThrow('\RuntimeException')
            ->duringDecreasePositionAction($element, 666, $request);
    }

    public function it_throws_runtime_exception_when_specified_entity_doesnt_exist(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        Request $request
    ) {
        $indexer->getData(666)->willThrow('FSi\Component\DataIndexer\Exception\RuntimeException');

        $this->shouldThrow('FSi\Component\DataIndexer\Exception\RuntimeException')
            ->duringIncreasePositionAction($element, 666, $request);

        $this->shouldThrow('FSi\Component\DataIndexer\Exception\RuntimeException')
            ->duringDecreasePositionAction($element, 666, $request);
    }

    public function it_decrease_position_when_decrease_position_action_called(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        ObjectManager $om,
        RouterInterface $router,
        Request $request
    ) {
        $indexer->getData(1)->willReturn($positionableEntity);

        $positionableEntity->decreasePosition()->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $router->generate('fsi_admin_crud_list', ['element' => 'slides'])
               ->willReturn('sample-path');

        $response = $this->decreasePositionAction($element, 1, $request);
        $response->shouldHaveType('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_increase_position_when_increase_position_action_called(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        ObjectManager $om,
        RouterInterface $router,
        Request $request
    ) {
        $indexer->getData(1)->willReturn($positionableEntity);

        $positionableEntity->increasePosition()->shouldBeCalled();

        $om->persist($positionableEntity)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $router->generate('fsi_admin_crud_list', ['element' => 'slides'])
               ->willReturn('sample-path');

        $response = $this->increasePositionAction($element, 1, $request);
        $response->shouldHaveType('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_redirects_to_redirect_uri_parameter_after_operation(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        PositionableInterface $positionableEntity,
        Request $request,
        ParameterBag $query
    ) {
        $query->get('redirect_uri')->willReturn('some_redirect_uri');

        $indexer->getData(1)->willReturn($positionableEntity);

        $response = $this->increasePositionAction($element, 1, $request);
        $response->shouldHaveType('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');

        $response = $this->decreasePositionAction($element, 1, $request);
        $response->shouldHaveType('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }
}
