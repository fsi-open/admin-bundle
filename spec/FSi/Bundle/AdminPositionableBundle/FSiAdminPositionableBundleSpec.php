<?php

namespace spec\FSi\Bundle\AdminPositionableBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FSiAdminPositionableBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
