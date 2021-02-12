<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\Manager\Visitor;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    public function let(Visitor $visitor): void
    {
        $this->beConstructedWith([$visitor]);
    }

    public function it_removes_element_by_id(Element $element): void
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }
}
