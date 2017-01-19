<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductionLineSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerFoo
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerBar
     */
    function it_is_created_with_workers($workerFoo, $workerBar)
    {
        $this->beConstructedWith(array($workerFoo, $workerBar));
        $this->count()->shouldReturn(2);
        $this->getWorkers()->shouldReturn(array($workerFoo, $workerBar));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerFoo
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerBar
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    function it_work_on_element_with_workers($workerFoo, $workerBar, $element)
    {
        $this->beConstructedWith(array($workerFoo, $workerBar));
        $workerBar->mount($element)->shouldBeCalled();
        $workerFoo->mount($element)->shouldBeCalled();

        $this->workOn($element);
    }
}
