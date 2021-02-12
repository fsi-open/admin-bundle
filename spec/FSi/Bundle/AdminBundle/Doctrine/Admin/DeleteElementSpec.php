<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyDeleteElement;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use stdClass;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DeleteElementSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry, ObjectManager $om): void
    {
        $this->beAnInstanceOf(MyDeleteElement::class);
        $this->beConstructedWith([]);

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
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

    public function it_deletes_object_from_object_manager(
        ObjectManager $om,
        stdClass $object
    ): void {
        $om->remove($object)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->apply($object);
    }
}
