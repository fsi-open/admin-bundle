<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Doctrine\Common\Persistence\ManagerRegistry;

class ResourceElementSpec extends ObjectBehavior
{
    function let(ManagerRegistry $registry)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyResourceElement');
        $this->setManagerRegistry($registry);
    }

    function it_return_repository(ManagerRegistry $registry, ObjectRepository $repository)
    {
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->shouldBecalled()->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }
}
