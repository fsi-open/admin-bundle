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
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use FSi\Component\Translatable\LocaleProvider;
use PhpSpec\ObjectBehavior;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyBatchElement;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

class BatchElementSpec extends ObjectBehavior
{
    public function let(LocaleProvider $localeProvider, ManagerRegistry $registry, ObjectManager $om): void
    {
        $this->beAnInstanceOf(MyBatchElement::class);
        $this->beConstructedWith([]);

        $localeProvider->getLocale()->willReturn('en');
        $this->setLocaleProvider($localeProvider);

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
}
