<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use PhpSpec\ObjectBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    function let(ManagerInterface $manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_handle_only_fully_qualified_class_names(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('FSiDemoBundle:News');
        $this->supports($configuration)->shouldReturn(false);
    }

    function it_supports_any_object_that_implements_element_interface(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\FormElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\ListElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\Manager');
        $this->supports($configuration)->shouldReturn(false);
    }
}
