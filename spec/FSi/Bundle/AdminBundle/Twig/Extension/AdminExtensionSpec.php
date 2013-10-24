<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Twig\Extension\AdminExtension');
    }

    function it_is_twig_extension()
    {
        $this->shouldBeAnInstanceOf('\Twig_Extension');
    }

    function it_return_globals_passed_in_constructor()
    {
        $this->beConstructedWith(array(
            'template' => 'test'
        ));

        $this->getGlobals()->shouldReturn(array(
            'template' => 'test'
        ));
    }
}
