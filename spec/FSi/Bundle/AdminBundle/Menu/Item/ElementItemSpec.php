<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\Element;
use PhpSpec\ObjectBehavior;

class ElementItemSpec extends ObjectBehavior
{
    public function let(Element $element): void
    {
        $element->getRoute()->willReturn('route');
        $element->getRouteParameters()->willReturn([]);
        $this->beConstructedWith('some name', $element);
    }

    public function it_has_default_options(): void
    {
        $this->getRoute()->shouldBe('route');
        $this->getRouteParameters()->shouldBe([]);
        $this->getOptions()->shouldReturn(['attr' => ['id' => null, 'class' => null], 'elements' => []]);
    }
}
