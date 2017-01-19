<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ElementItemSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    function let($element)
    {
        $this->beConstructedWith('some name', $element);
    }

    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(array('attr' => array('id' => null, 'class' => null), 'elements' => array()));
    }
}
