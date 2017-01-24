<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductionLineSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerFoo
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerBar
     */
    public function it_is_created_with_workers($workerFoo, $workerBar)
    {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $this->count()->shouldReturn(2);
        $this->getWorkers()->shouldReturn([$workerFoo, $workerBar]);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerFoo
     * @param \AdminPanel\Symfony\AdminBundle\Factory\Worker $workerBar
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    public function it_work_on_element_with_workers($workerFoo, $workerBar, $element)
    {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $workerBar->mount($element)->shouldBeCalled();
        $workerFoo->mount($element)->shouldBeCalled();

        $this->workOn($element);
    }
}
