<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     */
    function it_remove_element_by_id($element)
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager\Visitor $visitor
     */
    function it_accept_visitors($visitor)
    {
        $visitor->visitManager($this)->shouldBeCalled();
        $this->accept($visitor);
    }
}
