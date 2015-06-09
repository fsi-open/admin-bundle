<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CRUDElementSpec extends ObjectBehavior
{
    function let(ManagerRegistry $registry, ObjectManager $om)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyCrudElement');
        $this->beConstructedWith(array());

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    public function it_should_return_object_manager(ObjectManager $om)
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(ObjectManager $om, ObjectRepository $repository)
    {
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    public function it_should_save_object_at_object_manager(ObjectManager $om)
    {
        $om->persist(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->save(new \stdClass());
    }

    public function it_should_remove_object_from_object_manager(ObjectManager $om)
    {
        $om->remove(Argument::type('stdClass'))->shouldBeCalled();
        $om->flush()->shouldBeCalled();

        $this->delete(new \stdClass());
    }

    public function it_should_save_datagrid(ObjectManager $om)
    {
        $om->flush()->shouldBeCalled();

        $this->saveDataGrid();
    }

    public function it_should_have_doctrine_data_indexer(ManagerRegistry $registry, ObjectManager $om, ObjectRepository $repository, ClassMetadata $metadata)
    {
        $registry->getManagerForClass('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('FSi/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf('FSi\Component\DataIndexer\DoctrineDataIndexer');
    }
}
