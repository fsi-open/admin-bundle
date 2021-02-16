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
use FSi\Bundle\ResourceRepositoryBundle\Doctrine\ResourceRepository;
use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\spec\fixtures\MyResourceElement;

class ResourceElementSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry): void
    {
        $this->beAnInstanceOf(MyResourceElement::class);
        $this->setManagerRegistry($registry);
    }

    public function it_return_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ResourceRepository $repository
    ): void {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $om->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }

    public function it_throws_exception_when_repository_does_not_implement_resource_value_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository
    ): void {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->shouldThrow()->during('getRepository');
    }
}
