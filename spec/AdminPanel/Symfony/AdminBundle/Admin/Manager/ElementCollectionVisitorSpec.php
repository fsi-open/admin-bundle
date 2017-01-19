<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $adminElement
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     */
    function let($adminElement, $productionLine)
    {
        $this->beConstructedWith(array($adminElement), $productionLine);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $adminElement
     */
    function it_visit_manager_and_add_into_it_elements($manager, $productionLine, $adminElement)
    {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
