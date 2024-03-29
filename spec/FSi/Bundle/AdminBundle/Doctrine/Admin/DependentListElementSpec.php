<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\Translatable\LocaleProvider;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyDependentListElement;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

class DependentListElementSpec extends ObjectBehavior
{
    public function let(LocaleProvider $localeProvider, ManagerRegistry $registry, ObjectManager $om): void
    {
        $this->beAnInstanceOf(MyDependentListElement::class);
        $this->beConstructedWith([]);

        $localeProvider->getLocale()->willReturn('en');
        $this->setLocaleProvider($localeProvider);

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    public function it_is_dependent_batch_element(): void
    {
        $this->shouldHaveType(DependentElement::class);
        $this->shouldHaveType(ListElement::class);
    }

    public function it_returns_null_if_parent_object_id_is_not_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn(null);

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn(null);
    }

    public function it_returns_parent_object_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');
        $parentDataIndexer->getData('parent_object_id')->willReturn('parent_object');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn('parent_object');
    }

    public function its_route_parameters_contain_parent_object_id_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getRouteParameters()
            ->shouldHaveKeyWithValue(DependentElement::PARENT_REQUEST_PARAMETER, 'parent_object_id');
    }

    public function it_should_return_object_manager(ObjectManager $om): void
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(ObjectManager $om, ObjectRepository $repository): void
    {
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    public function it_should_have_doctrine_data_indexer(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository,
        ClassMetadata $metadata
    ): void {
        $registry->getManagerForClass('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('FSi/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf(DoctrineDataIndexer::class);
    }
}
