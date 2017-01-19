<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(array('attr' => array('id' => null, 'class' => null)));
    }
}
