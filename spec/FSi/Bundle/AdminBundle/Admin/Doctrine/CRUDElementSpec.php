<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CRUDElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyCrudElement');
        $this->beConstructedWith(array());
    }

    public function it_should_return_object_manager(ManagerRegistry $registry, ObjectManager $om)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($om);

        $this->setManagerRegistry($registry);
        $this->getObjectManager()->shouldReturn($om);
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(ManagerRegistry $registry, ObjectManager $om, ObjectRepository $repository)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($repository);

        $this->setManagerRegistry($registry);
        $this->getRepository()->shouldReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    public function it_should_save_object_at_object_manager(ManagerRegistry $registry, ObjectManager $om)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($om);
        $om->persist(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->setManagerRegistry($registry);
        $this->save(new \stdClass());
    }

    public function it_should_remove_object_from_object_manager(ManagerRegistry $registry, ObjectManager $om)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($om);
        $om->remove(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->setManagerRegistry($registry);
        $this->delete(new \stdClass());
    }

    public function it_should_save_datagrid(ManagerRegistry $registry, ObjectManager $om)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($om);
        $om->flush()->shouldBeCalled();

        $this->setManagerRegistry($registry);
        $this->saveDataGrid();
    }

    public function it_should_have_doctrine_data_indexer(ManagerRegistry $registry, ObjectManager $om, ObjectRepository $repository, ClassMetadata $metadata)
    {
        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $registry->getManagerForClass('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->shouldBeCalledTimes(1)->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('FSi/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf('FSi\Component\DataIndexer\DoctrineDataIndexer');
    }
}
