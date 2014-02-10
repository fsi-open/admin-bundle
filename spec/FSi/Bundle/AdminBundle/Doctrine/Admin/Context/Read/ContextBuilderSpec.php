<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use PhpSpec\ObjectBehavior;

class ContextBuilderSpec extends ObjectBehavior
{
    function let(Context $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(CRUDElement $element)
    {
        $this->supports('fsi_admin_crud_list', $element)->shouldReturn(true);
    }

    function it_build_context(Context $context, CRUDElement $element)
    {
        $context->setElement($element)->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }
}
