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
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Manager');
    }

    function it_return_groups_array(ElementInterface $element)
    {
        $element->getId()->shouldBeCalled()->willReturn('element_id');
        $this->addElement($element, 'group.basic');
        $this->getGroups()->shouldReturn(array(
            'group.basic'
        ));
    }

    function it_return_elements_by_group_id(ElementInterface $element)
    {
        $element->getId()->shouldBeCalled()->willReturn('element_id');

        $this->addElement($element, 'group.basic');
        $this->getElementsByGroup('group.basic')->shouldReturn(array(
            'element_id' => $element
        ));
    }

    function it_return_elements_without_group_id(ElementInterface $elementFoo, ElementInterface $elementBar)
    {
        $elementFoo->getId()->willReturn('element_foo');
        $elementBar->getId()->willReturn('element_bar');

        $this->addElement($elementFoo, 'foo');
        $this->addElement($elementBar);

        $this->getElementsWithoutGroup()->shouldReturn(array(
            'element_bar' => $elementBar
        ));
    }

    function it_remove_element_by_id(ElementInterface $element)
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }

    function it_remove_element_from_group(
        ElementInterface $element1,
        ElementInterface $element2
    ) {
        $element1->getId()->willReturn('el1');
        $this->addElement($element1, 'group.test');
        $element2->getId()->willReturn('el2');
        $this->addElement($element2, 'group.test');

        $this->getElementsByGroup('group.test')->shouldReturn(array('el1' => $element1, 'el2' => $element2));
        $this->removeElementFromGroup('el1');
        $this->getElementsByGroup('group.test')->shouldReturn(array('el2' => $element2));
    }

    function it_removes_element_from_group_during_remove_by_id(
        ElementInterface $element1,
        ElementInterface $element2
    ) {
        $element1->getId()->willReturn('el1');
        $this->addElement($element1, 'group.bar');
        $element2->getId()->willReturn('el2');
        $this->addElement($element2, 'group.bar');

        $this->getElementsByGroup('group.bar')->shouldReturn(array('el1' => $element1, 'el2' => $element2));
        $this->removeElement('el1');
        $this->getElementsByGroup('group.bar')->shouldReturn(array('el2' => $element2));
    }

    function it_removes_group(ElementInterface $element)
    {
        $element->getId()->willReturn('baz');
        $this->addElement($element, 'group.test1');
        $this->addElement($element, 'group.test2');

        $this->getGroups()->shouldReturn(array('group.test1', 'group.test2'));
        $this->removeGroup('group.test1');
        $this->getGroups()->shouldReturn(array('group.test2'));
    }

    function it_removes_group_when_last_element_is_removed_from_group(
        ElementInterface $element1,
        ElementInterface $element2
    ) {
        $element1->getId()->willReturn('element1');
        $this->addElement($element1, 'group.foo');

        $element2->getId()->willReturn('element2');
        $this->addElement($element2, 'group.foo');

        $this->getGroups()->shouldReturn(array('group.foo'));
        $this->removeElement('element1');
        $this->getGroups()->shouldReturn(array('group.foo'));
        $this->removeElement('element2');
        $this->getGroups()->shouldReturn(array());
    }
}
