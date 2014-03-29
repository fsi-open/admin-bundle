<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    function it_remove_element_by_id(ElementInterface $element)
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }
}
