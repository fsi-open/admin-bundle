<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Resource;

use FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Resource\ResourceContext;
use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;

class ResourceContextBuilderSpec extends ObjectBehavior
{
    function let(ResourceContext $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_resource_element(ResourceElement $element)
    {
        $this->supports('fsi_admin_resource', $element)->shouldReturn(true);
    }

    function it_build_context(ResourceElement $element, ResourceContext $context)
    {
        $context->setElement($element)->shouldBeCalled();

        $this->buildContext($element)->shouldReturn($context);
    }
}
