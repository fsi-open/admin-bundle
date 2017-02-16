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
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use PhpSpec\ObjectBehavior;

class ResourceElementSpec extends ObjectBehavior
{
    function let(ManagerRegistry $registry)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyResourceElement');
        $this->setManagerRegistry($registry);
    }

    function it_return_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ResourceValueRepository $repository
    ) {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $om->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }

    function it_throws_exception_when_repository_does_not_implement_resource_value_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository
    ) {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->shouldThrow()->during('getRepository');
    }
}
