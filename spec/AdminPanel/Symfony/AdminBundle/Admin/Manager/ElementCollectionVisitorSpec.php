<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $adminElement
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     */
    public function let($adminElement, $productionLine)
    {
        $this->beConstructedWith([$adminElement], $productionLine);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $adminElement
     */
    public function it_visit_manager_and_add_into_it_elements($manager, $productionLine, $adminElement)
    {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
