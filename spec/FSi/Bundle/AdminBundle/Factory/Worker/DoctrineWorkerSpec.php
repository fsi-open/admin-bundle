<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class DoctrineWorkerSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     */
    function let($managerRegistry)
    {
        $this->beConstructedWith($managerRegistry);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement $element
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     */
    function it_mount_datagrid_factory_to_elements_that_are_doctrine_elements($element, $managerRegistry)
    {
        $element->setManagerRegistry($managerRegistry)->shouldBeCalled();

        $this->mount($element);
    }
}
