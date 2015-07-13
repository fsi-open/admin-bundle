<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $adminElement
     * @param \FSi\Bundle\AdminBundle\Factory\ProductionLine $productionLine
     */
    function let($adminElement, $productionLine)
    {
        $this->beConstructedWith(array($adminElement), $productionLine);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \FSi\Bundle\AdminBundle\Factory\ProductionLine $productionLine
     * @param \FSi\Bundle\AdminBundle\Admin\Element $adminElement
     */
    function it_visit_manager_and_add_into_it_elements($manager, $productionLine, $adminElement)
    {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
