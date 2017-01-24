<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminPositionableBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FSiAdminPositionableBundleSpec extends ObjectBehavior
{
    public function it_is_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
