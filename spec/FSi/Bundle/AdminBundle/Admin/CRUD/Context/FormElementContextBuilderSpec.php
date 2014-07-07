<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

class FormElementContextBuilderSpec extends ObjectBehavior
{
    function let(FormElementContext $context, Request $request)
    {
        $this->beConstructedWith($context);
        $this->setRequest($request);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_form_element(FormElement $element)
    {
        $this->supports('fsi_admin_form', $element)->shouldReturn(true);
    }

    function it_build_context_without_object(FormElementContext $context, FormElement $element)
    {
        $context->setElement($element, null)->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }

    function it_build_context_with_object(
        FormElementContext $context,
        FormElement $element,
        DataIndexerInterface $dataIndexer,
        Request $request
    ) {
        $request->get('id', null)->willReturn(9);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData(9)->willReturn(array('object'));
        $context->setElement($element, array('object'))->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }
}
