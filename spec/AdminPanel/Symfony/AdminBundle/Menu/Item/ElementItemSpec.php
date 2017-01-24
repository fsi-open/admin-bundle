<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ElementItemSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    public function let($element)
    {
        $this->beConstructedWith('some name', $element);
    }

    public function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(['attr' => ['id' => null, 'class' => null], 'elements' => []]);
    }
}
