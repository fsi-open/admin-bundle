<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;

class MyResourceElement extends ResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin.main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'resources.main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}

class ResourceElementSpec extends ObjectBehavior
{
    function let(ManagerRegistry $registry)
    {
        $this->beAnInstanceOf('spec\FSi\Bundle\AdminBundle\Admin\Doctrine\MyResourceElement');
        $this->setManagerRegistry($registry);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement');
    }

    function it_return_repository(ManagerRegistry $registry, ObjectRepository $repository)
    {
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->shouldBecalled()->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }
}
