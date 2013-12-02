<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;

class ResourceContextBuilderSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, MapBuilder $builder, FormFactory $formFactory, Router $router)
    {
        $this->beConstructedWith($dispatcher, $formFactory, $router, $builder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ResourceContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_resource_element(ResourceElement $element)
    {
        $this->supports('fsi_admin_resource', $element)->shouldReturn(true);
    }

    function it_throws_exception_when_element_match_but_map_builder_is_missing(ResourceElement $element, EventDispatcher $dispatcher, FormFactory $formFactory, Router $router)
    {
        $this->beConstructedWith($dispatcher, $formFactory, $router, null);

        $this->shouldThrow(new ContextBuilderException("MapBuilder is missing. Make sure that FSiResourceRepositoryBundle is registered in AppKernel"))
            ->during('supports', array('fsi_admin_resource', $element));
    }

    function it_build_context(ResourceElement $element, MapBuilder $builder, FormFactory $formFactory, FormBuilder $formBuilder)
    {
        $builder->getMap()->willReturn(array(
            'resources' => array()
        ));

        $element->getResourceFormOptions()->willReturn(array());
        $element->getKey()->willReturn('resources');
        $formFactory->createBuilder('form', array(),array())->shouldBeCalled()->willReturn($formBuilder);
        $formBuilder->getForm()->shouldBeCalled();

        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ResourceContext');
    }
}
