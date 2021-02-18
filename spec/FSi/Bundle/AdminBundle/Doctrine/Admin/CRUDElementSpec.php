<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyCrudElement;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

class CRUDElementSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry, ObjectManager $om): void
    {
        $this->beAnInstanceOf(MyCrudElement::class);
        $this->beConstructedWith([]);

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    public function it_should_return_object_manager(ObjectManager $om): void
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(
        ObjectManager $om,
        ObjectRepository $repository
    ): void {
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    public function it_should_save_object_at_object_manager(
        ObjectManager $om,
        stdClass $object
    ): void {
        $om->persist(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->save($object);
    }

    public function it_should_remove_object_from_object_manager(
        ObjectManager $om,
        stdClass $object
    ): void {
        $om->remove(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->delete($object);
    }

    public function it_should_save_datagrid(ObjectManager $om): void
    {
        $om->flush()->shouldBeCalled();

        $this->saveDataGrid();
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
