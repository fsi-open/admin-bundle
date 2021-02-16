<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Factory\ProductionLine;
use PhpSpec\ObjectBehavior;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    public function let(Element $adminElement, ProductionLine $productionLine): void
    {
        $this->beConstructedWith([$adminElement], $productionLine);
    }

    public function it_visits_manager_and_add_into_it_elements(
        ManagerInterface $manager,
        ProductionLine $productionLine,
        Element $adminElement
    ): void {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
