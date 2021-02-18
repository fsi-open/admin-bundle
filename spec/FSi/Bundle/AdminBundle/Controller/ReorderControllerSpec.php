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
use Doctrine\ORM\EntityRepository;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Controller\ReorderController;
use FSi\Bundle\AdminBundle\Event\MovedDownTreeEvent;
use FSi\Bundle\AdminBundle\Event\MovedUpTreeEvent;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use FSi\Component\DataIndexer\Exception\RuntimeException;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ReorderControllerSpec extends ObjectBehavior
{
    public function let(
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        ObjectManager $om,
        NestedTreeRepository $repository,
        Request $request,
        ParameterBag $query
    ): void {
        $request->query = $query;
        $element->getId()->willReturn('category');
        $element->getDataIndexer()->willReturn($indexer);
        $element->getObjectManager()->willReturn($om);
        $element->getRepository()->willReturn($repository);
        $element->getRoute()->willReturn('fsi_admin_crud_list');
        $element->getRouteParameters()->willReturn(['element' => 'category']);

        $this->beConstructedWith($router, $eventDispatcher);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ReorderController::class);
    }

    public function it_moves_up_item_when_move_up_action_called(
        CRUDElement $element,
        NestedTreeRepository $repository,
        stdClass $category,
        ObjectManager $om,
        RouterInterface $router,
        DoctrineDataIndexer $indexer,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): void {
        $indexer->getData('1')->willReturn($category);

        $repository->moveUp($category)->shouldBeCalled();

        $om->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(MovedUpTreeEvent::class))->shouldBeCalled();

        $router->generate(
            'fsi_admin_crud_list',
            Argument::withEntry('element', 'category')
        )->willReturn('sample-path');

        $response = $this->moveUpAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_moves_down_item_when_move_down_action_called(
        CRUDElement $element,
        NestedTreeRepository $repository,
        stdClass $category,
        ObjectManager $om,
        RouterInterface $router,
        DoctrineDataIndexer $indexer,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): void {
        $indexer->getData('1')->willReturn($category);

        $repository->moveDown($category)->shouldBeCalled();

        $om->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(MovedDownTreeEvent::class))->shouldBeCalled();

        $router->generate(
            'fsi_admin_crud_list',
            Argument::withEntry('element', 'category')
        )->willReturn('sample-path');

        $response = $this->moveDownAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('sample-path');
    }

    public function it_throws_runtime_exception_when_specified_entity_doesnt_exist(
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        Request $request
    ): void {
        $indexer->getData('666')->willThrow(RuntimeException::class);

        $this->shouldThrow(RuntimeException::class)->duringMoveUpAction($element, '666', $request);
        $this->shouldThrow(RuntimeException::class)->duringMoveDownAction($element, '666', $request);
    }

    public function it_throws_exception_when_entity_doesnt_have_correct_repository(
        CRUDElement $element,
        EntityRepository $repository,
        DoctrineDataIndexer $indexer,
        stdClass $category,
        Request $request
    ): void {
        $indexer->getData('666')->willReturn($category);
        $element->getRepository()->willReturn($repository);

        $this->shouldThrow(InvalidArgumentException::class)->duringMoveUpAction($element, '666', $request);
        $this->shouldThrow(InvalidArgumentException::class)->duringMoveDownAction($element, '666', $request);
    }

    public function it_redirects_to_redirect_uri_parameter_after_operation(
        EventDispatcherInterface $eventDispatcher,
        CRUDElement $element,
        DoctrineDataIndexer $indexer,
        stdClass $category,
        Request $request,
        ParameterBag $query
    ): void {
        $query->get('redirect_uri')->willReturn('some_redirect_uri');
        $indexer->getData('1')->willReturn($category);

        $eventDispatcher->dispatch(Argument::type(MovedUpTreeEvent::class))->shouldBeCalled();

        $response = $this->moveUpAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');

        $eventDispatcher->dispatch(Argument::type(MovedDownTreeEvent::class))->shouldBeCalled();

        $response = $this->moveDownAction($element, '1', $request);
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }
}
