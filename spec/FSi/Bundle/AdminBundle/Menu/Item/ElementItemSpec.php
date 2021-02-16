<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\Element;
use PhpSpec\ObjectBehavior;

class ElementItemSpec extends ObjectBehavior
{
    public function let(Element $element): void
    {
        $this->beConstructedWith('some name', $element);
    }

    public function it_has_default_options(): void
    {
        $this->getOptions()->shouldReturn(['attr' => ['id' => null, 'class' => null], 'elements' => []]);
    }
}
