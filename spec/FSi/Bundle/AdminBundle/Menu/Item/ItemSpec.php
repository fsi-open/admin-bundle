<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(array('id' => null, 'class' => null));
    }
}
