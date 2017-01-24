<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    public function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(['attr' => ['id' => null, 'class' => null]]);
    }
}
