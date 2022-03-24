<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use PhpSpec\ObjectBehavior;

class ProductionLineSpec extends ObjectBehavior
{
    public function it_is_created_with_workers(Worker $workerFoo, Worker $workerBar): void
    {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $this->count()->shouldReturn(2);
        $this->getWorkers()->shouldReturn([$workerFoo, $workerBar]);
    }

    public function it_work_on_element_with_workers(
        Worker $workerFoo,
        Worker $workerBar,
        Element $element
    ): void {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $workerBar->mount($element)->shouldBeCalled();
        $workerFoo->mount($element)->shouldBeCalled();

        $this->workOn($element);
    }
}
