<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

class ResourceRepositoryContextBuilderSpec extends ObjectBehavior
{
    function let(ResourceRepositoryContext $context)
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

    function it_build_context(ResourceElement $element, ResourceRepositoryContext $context)
    {
        $context->setElement($element)->shouldBeCalled();

        $this->buildContext($element)->shouldReturn($context);
    }
}
