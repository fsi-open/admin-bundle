<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Factory\ProductionLine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    function let(Element $adminElement, ProductionLine $productionLine)
    {
        $this->beConstructedWith(array($adminElement), $productionLine);
    }

    function it_visit_manager_and_add_into_it_elements(
        Manager $manager,
        ProductionLine $productionLine,
        Element $adminElement
    ) {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
