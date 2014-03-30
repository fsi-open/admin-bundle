<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use PhpSpec\ObjectBehavior;

class DoctrineWorkerSpec extends ObjectBehavior
{
    function let(ManagerRegistry $managerRegistry)
    {
        $this->beConstructedWith($managerRegistry);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(CRUDElement $element, ManagerRegistry $managerRegistry)
    {
        $element->setManagerRegistry($managerRegistry)->shouldBeCalled();

        $this->mount($element);
    }
}
