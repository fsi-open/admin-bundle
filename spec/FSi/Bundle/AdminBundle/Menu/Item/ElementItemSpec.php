<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\Element;
use PhpSpec\ObjectBehavior;

class ElementItemSpec extends ObjectBehavior
{
    function let(Element $element)
    {
        $this->beConstructedWith('some name', $element);
    }

    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(array('attr' => array('id' => null, 'class' => null), 'elements' => array()));
    }
}
