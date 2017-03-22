<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DeleteElementSpec extends ObjectBehavior
{
    function let(ManagerRegistry $registry, ObjectManager $om)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyDeleteElement');
        $this->beConstructedWith([]);

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     */
    public function it_should_return_object_manager(ObjectManager $om)
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(ObjectManager $om, ObjectRepository $repository)
    {
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $registry
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     */
    public function it_should_have_doctrine_data_indexer(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository,
        ClassMetadata $metadata
    ) {
        $registry->getManagerForClass('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('FSi/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf('FSi\Component\DataIndexer\DoctrineDataIndexer');
    }

    public function it_deletes_object_from_object_manager(ObjectManager $om)
    {
        $object = new \stdClass();

        $om->remove($object)->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->apply($object);
    }
}
