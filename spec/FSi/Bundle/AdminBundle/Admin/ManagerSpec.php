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
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Manager');
    }

    function it_return_groups_array(ElementInterface $element)
    {
        $element->getId()->shouldBeCalled()->willReturn('element_id');
        $this->addelement($element, 'group.basic');
        $this->getGroups()->shouldReturn(array(
            'group.basic'
        ));
    }

    function it_return_elements_by_group_id(ElementInterface $element)
    {
        $element->getId()->shouldBeCalled()->willReturn('element_id');

        $this->addelement($element, 'group.basic');
        $this->getElementsByGroup('group.basic')->shouldReturn(array(
            'element_id' => $element
        ));
    }

    function it_return_elements_without_group_id(ElementInterface $elementFoo, ElementInterface $elementBar)
    {
        $elementFoo->getId()->shouldBeCalled()->willReturn('element_foo');
        $elementBar->getId()->shouldBeCalled()->willReturn('element_bar');

        $this->addElement($elementFoo, 'foo');
        $this->addElement($elementBar);

        $this->getElementsWithoutGroup()->shouldReturn(array(
            'element_bar' => $elementBar
        ));
    }
}
